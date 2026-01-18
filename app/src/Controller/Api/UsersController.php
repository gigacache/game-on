<?php
declare(strict_types=1);

namespace App\Controller\Api;

class UsersController extends ApiController
{
    /**
     * Create a new user
     *
     * @return void
     */
    public function create()
    {
        $this->validateRequest('POST');
        $data = $this->request->getData();

        $user = $this->Users->newEmptyEntity();
        $user = $this->Users->patchEntity($user, $data);

        if (!$this->Users->save($user)) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Unable to save user.',
                'errors' => $user->getErrors(),
            ], 400);

            return;
        }

        $this->buildResponse([
            'success' => true,
            'message' => 'User was created',
            'errors' => null,
            'user' => [
                'id' => $user->id,
                'uuid' => $user->uuid,
                'full_name' => $user->full_name,
                'email' => $user->email,
                '_client_token' => $user->_client_token ?? null,
            ],
        ], 201);
    }
}
