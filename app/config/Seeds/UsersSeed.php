<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Users seed.
 */
class UsersSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'uuid' => 'ac6b4e25-32fb-4889-bf41-48036028d4dd',
                'first_name' => 'Homer',
                'last_name' => 'Simpson',
                'email' => 'h.simpson1@springfield.co.uk',
                'token_hash' => '51703d1dde30ac118b923a162231758a960f00ce6dbca6c85f432e80e4cf996c',
                'token_created_at' => '2026-01-18 15:09:19',
                'is_token_active' => 1,
                'created' => '2026-01-18 15:09:19',
                'updated' => '2026-01-18 15:09:19',
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
