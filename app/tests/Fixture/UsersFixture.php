<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'uuid' => 'f100aa92-a5f5-4b62-80ea-1c0b8da6bd45',
                'first_name' => 'Homer',
                'last_name' => 'Simpson',
                'email' => 'h.simpson@springfield.co.uk',
                'token_hash' => '51703d1dde30ac118b923a162231758a960f00ce6dbca6c85f432e80e4cf996c',
                'token_created_at' => '2026-01-18 13:44:07',
                'is_token_active' => true,
                'created' => '2026-01-18 13:44:07',
                'updated' => '2026-01-18 13:44:07',
            ],
        ];
        parent::init();
    }
}
