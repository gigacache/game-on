<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Model\Table\EventsTable;
use Cake\Datasource\Exception\RecordNotFoundException;

class BookingsController extends ApiController
{
    protected ?EventsTable $Events;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Events = $this->fetchTable('Events');
    }

    /**
     * Create a new booking
     *
     * @return void
     */
    public function create()
    {
        $this->validateRequest('POST');
        // can do this in the cakephp5 middleware but to make it simple
        if (!$this->authenticate()) {
            return;
        }

        $data = $this->request->getData();
        $booking = $this->Bookings->newEmptyEntity();
        $booking = $this->Bookings->patchEntity($booking, $data);
        $booking->booked_by = $this->authenticatedUser->id;

        try {
            $event = $this->Events->get($data['event_id']);
        } catch (RecordNotFoundException $e) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Event not found',
                'errors' => 'Event not found',
            ], 404);

            return;
        }

        $bookingsForCurrentEvent = $this->Bookings
            ->find('forEvent', eventId: $event->id)
            ->count();

        if ($bookingsForCurrentEvent >= $event->max_attendees) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Sorry the event is full',
                'errors' => 'Sorry the event is full',
            ], 409);

            return;
        }

        $booking->organised_by = $this->authenticatedUser->id;

        if (!$this->Bookings->save($booking)) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Unable to save booking.',
                'errors' => $booking->getErrors(),
            ], 400);

            return;
        }

        $this->buildResponse([
            'success' => true,
            'message' => 'Booking was created',
            'errors' => null,
            'booking' => $booking,
        ], 201);
    }
}
