<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

use App\Model\Entity\Event;
use App\Model\Table\EventsTable;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class EventsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    // Authorization Token
    public const HOMERS_FIXTURE_TOKEN = 'c195263efe55ed73c15f0dbd8afa1aa31a88d0f35c14acd790d565210c0a947e';

    protected array $fixtures = [
        'app.Users',
        'app.Events',
    ];

    protected ?EventsTable $Events;

    /**
     * Test set up table.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Events = $this->getTableLocator()->get('Events');
    }

    /**
     * Test tear down table.
     */
    public function tearDown(): void
    {
        unset($this->Events);
        parent::tearDown();
    }

    /**
     * Test creating an event successfully.
     */
    public function testCreateEventSuccess(): void
    {
        $data = [
            'name' => 'Springfield Marathon',
            'sport' => 'Running',
            'sponsor' => 'Kwik-E-Mart',
            'max_attendees' => 2,
            'date_of_event' => '2026-04-01',
            'location_country_iso' => 'US',
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('/api/events/', json_encode($data));

        $this->assertResponseCode(201);
        $this->assertContentType('application/json');
        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertTrue($response['success']);
        $this->assertSame('Event was created', $response['message']);
        $this->assertArrayHasKey('event', $response);
        $this->assertSame($data['name'], $response['event']['name']);
        $this->assertSame($data['sport'], $response['event']['sport']);
        $this->assertSame($data['sponsor'], $response['event']['sponsor']);
    }

    /**
     * Test creating an event fails when required fields are missing.
     */
    public function testCreateEventFailure(): void
    {
        $data = [
            'sport' => 'Running',
            'sponsor' => 'Kwik-E-Mart',
            'max_attendees' => 2,
            'date_of_event' => '2026-04-01',
            'location_country_iso' => 'US',
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('/api/events/', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertContentType('application/json');
        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertFalse($response['success']);
        $this->assertSame('Unable to save event.', $response['message']);
        $this->assertArrayHasKey('name', $response['errors']);
    }

    /**
     * Test fetching events (GET request)
     */
    public function testGetEvents(): void
    {
        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Accept' => 'application/json',
            ],
        ]);

        $this->get('/api/events?limit=1');
        $this->assertResponseCode(200);
        $this->assertContentType('application/json');
        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('events', $response);
    }

    /**
     * Test cancelling events (DELETE request / soft delete)
     */
    public function testDeleteEvent(): void
    {
        $createData = [
            'name' => 'Test Event',
            'sport' => 'Running',
            'sponsor' => 'Test Sponsor',
            'max_attendees' => 10,
            'date_of_event' => '2026-05-01',
            'location_country_iso' => 'US',
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);
        $this->post('/api/events/create', json_encode($createData));
        $response = json_decode((string)$this->_response->getBody(), true);
        $eventId = $response['event']['id'];
        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Accept' => 'application/json',
            ],
        ]);
        $this->delete('/api/events/delete/' . $eventId);
        $this->assertResponseCode(200);
        $this->assertContentType('application/json');

        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertTrue($response['success']);
        $this->assertSame('Event has been cancelled.', $response['message']);

        $event = $this->Events->get($eventId);
        $this->assertSame(Event::STATUS_CANCELLED, $event->status);
    }

    /**
     * Test updating an existing event (PUT request)
     */
    public function testUpdateEvent(): void
    {
        $createData = [
            'name' => 'Old Event Name',
            'sport' => 'Running',
            'sponsor' => 'Old Sponsor',
            'max_attendees' => 10,
            'date_of_event' => '2026-05-01',
            'location_country_iso' => 'US',
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('/api/events/', json_encode($createData));

        $this->assertResponseCode(201);
        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertTrue($response['success']);
        $eventId = $response['event']['id'];

        $updateData = [
            'id' => $eventId,
            'name' => 'Updated Event Name',
            'sport' => 'Cycling',
            'sponsor' => 'New Sponsor',
            'max_attendees' => 20,
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->put('/api/events/', json_encode($updateData));

        $this->assertResponseCode(200);
        $this->assertContentType('application/json');
        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertTrue($response['success']);
        $this->assertSame('Event was updated', $response['message']);
        $this->assertArrayHasKey('event', $response);
        $this->assertSame($updateData['name'], $response['event']['name']);
        $this->assertSame($updateData['sport'], $response['event']['sport']);
        $this->assertSame($updateData['sponsor'], $response['event']['sponsor']);
        $this->assertSame($updateData['max_attendees'], $response['event']['max_attendees']);
    }
}
