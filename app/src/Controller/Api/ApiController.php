<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\MethodNotAllowedException;

class ApiController extends AppController
{
    protected UsersTable $Users;

    protected ?User $authenticatedUser;

    /**
     * initialize controller
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * Validate the request method and authenticate the user
     *
     * @param string ...$httpMethods Allowed HTTP methods
     * @return void
     * @throws \Cake\Http\Exception\MethodNotAllowedException|\Cake\Http\Exception\UnauthorizedException
     */
    protected function validateRequest(string ...$httpMethods): void
    {
        if ($httpMethods) {
            try {
                $this->request->allowMethod($httpMethods);
            } catch (MethodNotAllowedException $error) {
                throw new MethodNotAllowedException('Invalid request method.');
            }
        }
    }

    /**
     * Authenticate the user using Authorization header
     * (can do this in the cakephp5 middleware)
     *
     * @return bool
     * @throws \Cake\Http\Exception\UnauthorizedException
     */
    protected function authenticate(): bool
    {
        $token = $this->request->getHeaderLine('Authorization');
        if (!$token) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Missing Authorization token',
                'errors' => 'Unauthorized Exception',
            ], 401);

            return false;
        }

        $this->authenticatedUser = $this->Users->find()
            ->where([
                'token_hash' => hash('sha256', $token),
                'is_token_active' => true,
            ])
            ->first();

        if (!$this->authenticatedUser) {
            $this->buildResponse([
                'success' => false,
                'message' => 'Invalid or inactive token',
                'errors' => 'Unauthorized Exception',
            ], 401);

            return false;
        }

        return true;
    }

    /**
     * Build a JSON API response with CORS headers.
     *
     * This method prepares the controller to return a JSON response by:
     *   - Setting the view class to 'Json'
     *   - Passing the provided data to the view
     *   - Setting CORS headers on the response
     *   - Setting the HTTP status code
     *
     * The actual JSON response is rendered by CakePHP using the `_serialize` option,
     * so the controller action does not need to return a response object.
     *
     * @param object|array $data The data to include in the JSON response.
     * @param int $status The HTTP status code to set on the response (default 200).
     */
    protected function buildResponse(array|object $data, int $status = 200)
    {
        $this->viewBuilder()->setClassName('Json');
        $this->set('response', $data);
        $this->viewBuilder()->setOption('serialize', 'response');

        $this->response = $this->response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withStatus($status);
    }
}
