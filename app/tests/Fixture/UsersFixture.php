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
                'token_hash' => 'ae1804f14289278d25c33c1002122ff915fe199ae182c67a3f4b3338b5756654',
                'token_created_at' => '2026-01-18 13:44:07',
                'is_token_active' => true,
                'created' => '2026-01-18 13:44:07',
                'updated' => '2026-01-18 13:44:07',
            ],
        ];
        parent::init();
    }
}
