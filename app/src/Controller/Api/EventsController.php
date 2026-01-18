<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Datasource\Exception\RecordNotFoundException;

class EventsController extends ApiController
{
    /**
     * Get events
     *
     * @return void
     */
    public function get()
    {
        $this->validateRequest('GET');

        if (!$this->authenticate()) {
            return;
        }

        $limit = $this->request->getQuery('limit', 10);
        $events = $this->Events->find()->limit((int)$limit)->all();

        if ($events->isEmpty()) {
            $this->buildResponse([
                'success' => false,
                'message' => 'No events found',
                'errors' => null,
            ], 404);

            return;
        }

        $this->buildResponse([
            'success' => true,
            'message' => 'Events fetched successfully',
            'errors' => null,
            'events' => $events,
        ], 200);
    }

    /**
     * Create a new event
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
        $event = $this->Events->newEmptyEntity();
        $event = $this->Events->patchEntity($event, $data);

        $event->organised_by = $this->authenticatedUser->id;

        if (!$this->Events->save($event)) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Unable to save event.',
                'errors' => $event->getErrors(),
            ], 400);

            return;
        }

        $this->buildResponse([
            'success' => true,
            'message' => 'Event was created',
            'errors' => null,
            'event' => $event,
        ], 201);
    }

    /**
     * Update an event
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
            $event = $this->Events->get($data['id']);
        } catch (RecordNotFoundException $e) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Event not found',
                'errors' => 'Event not found',
            ], 404);

            return;
        }

        $event = $this->Events->patchEntity($event, $data);

        if (!$event->getDirty()) {
            $this->buildResponse([
                'success' => true,
                'message' => 'No changes made',
                'errors' => null,
                'event' => $event,
            ], 200);

            return;
        }

        if (!$this->Events->save($event)) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Unable to save event.',
                'errors' => $event->getErrors(),
            ], 400);

            return;
        }

        $this->buildResponse([
            'success' => true,
            'message' => 'Event was updated',
            'errors' => null,
            'event' => $event,
        ], 200);
    }

    /**
     * Delete an event
     *
     * @param string $id
     * @return void
     */
    public function delete(string $id)
    {
        $this->validateRequest('DELETE');
        // can do this in the cakephp5 middleware but to make it simple
        if (!$this->authenticate()) {
            return;
        }

        try {
            $event = $this->Events->get($id);
        } catch (RecordNotFoundException $e) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Event not found',
                'errors' => 'Event not found',
            ], 404);

            return;
        }

        if (!$this->Events->delete($event)) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Unable to delete event',
                'errors' => $event->getErrors(),
            ], 400);

            return;
        }

        $this->buildResponse([
            'success' => true,
            'message' => 'Event deleted successfully',
            'errors' => null,
        ], 200);
    }
}
