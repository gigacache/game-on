<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

use App\Model\Table\AttendeesTable;
use App\Model\Table\UsersTable;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class AttendeesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    // Authorization Token
    public const HOMERS_FIXTURE_TOKEN = 'c195263efe55ed73c15f0dbd8afa1aa31a88d0f35c14acd790d565210c0a947e';

    protected array $fixtures = [
        'app.Users',
        'app.Attendees',
    ];

    protected ?AttendeesTable $Attendees;
    protected ?UsersTable $Users;

    /**
     * Test set up table.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Attendees = $this->getTableLocator()->get('Attendees');
        $this->Users = $this->getTableLocator()->get('Users');
    }

    /**
     * Test tear down table.
     */
    public function tearDown(): void
    {
        unset($this->Attendees);
        unset($this->Users);
        parent::tearDown();
    }

    /**
     * Test creating an attendee successfully (authenticated).
     */
    public function testCreateAttendeeSuccess(): void
    {
        $data = [
            'first_name' => 'Homer',
            'last_name' => 'Simpson',
            'email' => 'homer.simpson+' . uniqid() . '@springfield.co.uk',
            'mobile' => '07958430593212',
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('/api/attendees/', json_encode($data));

        $this->assertResponseCode(201);
        $this->assertContentType('application/json');

        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertTrue($response['success']);
        $this->assertSame('Attendee was created', $response['message']);
        $this->assertArrayHasKey('attendee', $response);

        $this->assertSame($data['email'], $response['attendee']['email']);
        $this->assertSame(1, $response['attendee']['registered_by']);
    }

    /**
     * Test creating an attendee fails when required fields are missing.
     */
    public function testCreateAttendeeFailure(): void
    {
        $data = [
            'first_name' => 'Bart',
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('/api/attendees/', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertContentType('application/json');

        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertFalse($response['success']);
        $this->assertSame('Unable to save attendee.', $response['message']);
        $this->assertArrayHasKey('email', $response['errors']);
    }

    /**
     * Test attendee registration (unauthenticated).
     */
    public function testRegisterAttendeeSuccess(): void
    {
        $data = [
            'first_name' => 'Lisa',
            'last_name' => 'Simpson',
            'email' => 'lisa.simpson+' . uniqid() . '@springfield.co.uk',
            'mobile' => '07958430593212',
        ];

        $this->post('/api/attendees/register/', $data, ['Content-Type' => 'application/json']);
        $this->assertResponseCode(201);
        $this->assertContentType('application/json');

        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertTrue($response['success']);
        $this->assertSame(
            'Registration successful! Please log in to continue.',
            $response['message'],
        );

        $this->assertNull($response['attendee']['registered_by']);
    }

    /**
     * Test updating an attendee email only
     */
    public function testUpdateAttendeeEmailOnly(): void
    {
        $createData = [
            'first_name' => 'Clancy',
            'last_name' => 'Wiggum',
            'email' => 'chief.wiggum+' . uniqid() . '@springfield.co.uk',
            'mobile' => '07000000000',
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->post('/api/attendees/', json_encode($createData));
        $this->assertResponseCode(201);
        $createResponse = json_decode((string)$this->_response->getBody(), true);
        $this->assertTrue($createResponse['success']);
        $attendeeId = $createResponse['attendee']['id'];
        $updateData = [
            'id' => $attendeeId,
            'email' => 'chief.wiggum.updated+' . uniqid() . '@springfield.co.uk',
        ];

        $this->configRequest([
            'headers' => [
                'Authorization' => self::HOMERS_FIXTURE_TOKEN,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->put('/api/attendees/', json_encode($updateData));
        $this->assertResponseCode(200);
        $this->assertContentType('application/json');
        $updateResponse = json_decode((string)$this->_response->getBody(), true);
        $this->assertTrue($updateResponse['success']);
        $this->assertSame('Attendee was updated', $updateResponse['message']);
        $this->assertArrayHasKey('attendee', $updateResponse);
        $this->assertSame(
            $updateData['email'],
            $updateResponse['attendee']['email'],
        );
    }
}
