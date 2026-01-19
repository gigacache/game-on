<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

use App\Model\Table\BookingsTable;
use App\Model\Table\EventsTable;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class BookingsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    // Authorization Token for authenticated user
    public const HOMERS_FIXTURE_TOKEN = 'c195263efe55ed73c15f0dbd8afa1aa31a88d0f35c14acd790d565210c0a947e';

    protected array $fixtures = [
        'app.Users',
        'app.Attendees',
        'app.Events',
        'app.Bookings',
    ];

    protected ?BookingsTable $Bookings;
    protected ?EventsTable $Events;

    public function setUp(): void
    {
        parent::setUp();
        $this->Bookings = $this->getTableLocator()->get('Bookings');
        $this->Events = $this->getTableLocator()->get('Events');
    }

    public function tearDown(): void
    {
        unset($this->Bookings);
        unset($this->Events);
        parent::tearDown();
    }

    /**
     * Test creating a booking successfully
     */
    public function testCreateBookingSuccess(): void
    {
        $attendeeId = 2;
        $eventId = 1;
        $data = [
            'event_id' => $eventId,
            'attendee_id' => $attendeeId,
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('/api/bookings/', json_encode($data));
        $this->assertResponseCode(201);
        $this->assertContentType('application/json');
        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertTrue($response['success']);
        $this->assertSame('Booking was created', $response['message']);
        $this->assertArrayHasKey('booking', $response);
        $this->assertSame($attendeeId, $response['booking']['attendee_id']);
        $this->assertSame($eventId, $response['booking']['event_id']);
    }

    /**
     * Test booking the same attendee twice (duplicate)
     */
    public function testDuplicateAttendeeBooking(): void
    {
        $event = $this->Events->get(1);
        $attendeeId = 1;
        $booking = $this->Bookings->newEmptyEntity();
        $booking = $this->Bookings->patchEntity($booking, [
            'event_id' => $event->id,
            'attendee_id' => $attendeeId,
            'booked_by' => 1,
        ]);
        $this->Bookings->save($booking);
        $data = [
            'event_id' => $event->id,
            'attendee_id' => $attendeeId,
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('/api/bookings/', json_encode($data));
        $this->assertResponseCode(400);
        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertFalse($response['success']);
        $this->assertSame('Unable to save booking.', $response['message']);
        $this->assertArrayHasKey('attendee_id', $response['errors']);
    }

    /**
     * Test booking an event that's already full
     */
    public function testBookingFullEvent(): void
    {
        $event = $this->Events->newEmptyEntity();
        $event = $this->Events->patchEntity($event, [
            'name' => 'Krusty Fun Run',
            'sport' => 'Running',
            'sponsor' => 'Krusty Burger',
            'max_attendees' => 1,
            'date_of_event' => '2026-05-01',
            'location_country_iso' => 'US',
        ]);
        $this->Events->saveOrFail($event);

        $attendeeId1 = 1;
        $booking1 = $this->Bookings->newEmptyEntity();
        $booking1 = $this->Bookings->patchEntity($booking1, [
            'event_id' => $event->id,
            'attendee_id' => $attendeeId1,
            'booked_by' => 1,
        ]);
        $this->Bookings->saveOrFail($booking1);

        $attendeeId2 = 2;
        $data = [
            'event_id' => $event->id,
            'attendee_id' => $attendeeId2,
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);
        $this->post('/api/bookings/', json_encode($data));
        $this->assertResponseCode(409);
        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertFalse($response['success']);
        $this->assertSame('Sorry the event is full', $response['message']);
    }
}
