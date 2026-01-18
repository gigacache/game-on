<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Datasource\Exception\RecordNotFoundException;

class AttendeesController extends ApiController
{
    /**
     * Create a new attendee (Users authenticated endpoint)
     *
     * @return void
     */
    public function create()
    {
        $this->validateRequest('POST');
        if (!$this->authenticate()) {
            return;
        }
        $data = $this->request->getData();
        $attendee = $this->Attendees->newEmptyEntity();
        $attendee = $this->Attendees->patchEntity($attendee, $data);

        $attendee->registered_by = $this->authenticatedUser->id;

        if (!$this->Attendees->save($attendee)) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Unable to save attendee.',
                'errors' => $attendee->getErrors(),
            ], 400);

            return;
        }

        $this->buildResponse([
            'success' => true,
            'message' => 'Event was created',
            'errors' => null,
            'attendee' => $attendee,
        ], 201);
    }

    /**
     * Attendee to reister (Attendees unauthenticated endpoint)
     *
     * @return void
     */
    public function register()
    {
        $this->validateRequest('POST');
        $data = $this->request->getData();
        $attendee = $this->Attendees->newEmptyEntity();
        $attendee = $this->Attendees->patchEntity($attendee, $data);

        $attendee->registered_by = null;

        if (!$this->Attendees->save($attendee)) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
                'errors' => $attendee->getErrors(),
            ], 400);

            return;
        }

        $this->buildResponse([
            'success' => true,
            'message' => 'Registration successful! Please log in to continue.',
            'errors' => null,
            'attendee' => $attendee,
        ], 201);
    }

    /**
     * Update an attendees infomation
     *
     * @return void
     */
    public function update()
    {
        $this->validateRequest('PUT');
        // can do this in the cakephp5 middleware but to make it simple
        if (!$this->authenticate()) {
            return;
        }

        $data = $this->request->getData();

        try {
            $attendee = $this->Attendees->get($data['id']);
        } catch (RecordNotFoundException $e) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Attendee not found',
                'errors' => 'Attendee not found',
            ], 404);

            return;
        }

        $attendee = $this->Attendees->patchEntity($attendee, $data);

        if (!$attendee->getDirty()) {
            $this->buildResponse([
                'success' => true,
                'message' => 'No changes made',
                'errors' => null,
                'event' => $attendee,
            ], 200);

            return;
        }

        if (!$this->Attendees->save($attendee)) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Unable to save attendee',
                'errors' => $attendee->getErrors(),
            ], 400);

            return;
        }

        $this->buildResponse([
            'success' => true,
            'message' => 'Attendee was updated',
            'errors' => null,
            'event' => $attendee,
        ], 200);
    }
}
