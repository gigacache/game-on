<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

use App\Model\Table\UsersTable;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Users',
    ];

    protected ?UsersTable $Users;

    /**
     * Test set up table.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Users = $this->getTableLocator()->get('Users');
    }

    /**
     * Test tear down table.
     */
    public function tearDown(): void
    {
        unset($this->Users);
        parent::tearDown();
    }

    /**
     * Test creating a user.
     */
    public function testCreateUserSuccess(): void
    {
        $data = [
            'first_name' => 'Homer',
            'last_name' => 'Simpson',
            'email' => 'homer.simpson+' . uniqid() . '@springfield.co.uk',
        ];
        $this->post('/api/users/create', $data, ['Content-Type' => 'application/json']);
        $this->assertResponseCode(201);
        $this->assertContentType('application/json');
        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertTrue($response['success']);
        $this->assertSame('User was created', $response['message']);
        $this->assertArrayHasKey('user', $response);
        $this->assertSame($data['email'], $response['user']['email']);
        $this->assertSame('Homer Simpson', $response['user']['full_name']);
    }

    /**
     * Test creating a user fails when required fields are missing.
     */
    public function testCreateUserFailure(): void
    {
        $data = [
            'first_name' => 'Bart',
            'last_name' => 'Simpson',
        ];
        $this->post('/api/users/create', $data, ['Content-Type' => 'application/json']);
        $this->assertResponseCode(400);
        $this->assertContentType('application/json');
        $response = json_decode((string)$this->_response->getBody(), true);
        $this->assertFalse($response['success']);
        $this->assertSame('Unable to save user.', $response['message']);
        $this->assertArrayHasKey('email', $response['errors']);
    }
}
