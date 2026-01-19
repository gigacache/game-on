<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Attendees seed.
 */
class AttendeesSeed extends BaseSeed
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
                'uuid' => '494198bb-f775-44bd-a156-a5b9b374798e',
                'first_name' => 'Moe',
                'last_name' => 'Szyslak',
                'mobile' => '07958385812',
                'email' => 'moe.szyslak@moesbar.co.uk',
                'registered_by' => 1,
                'created' => '2026-01-18 19:45:29',
                'modified' => '2026-01-18 19:49:08',
            ],
            [
                'id' => 2,
                'uuid' => 'f97d4610-c554-40e3-87f6-b32593da766a',
                'first_name' => 'Marge',
                'last_name' => 'Simpson',
                'mobile' => '07958430593212',
                'email' => 'marge.simpson@springfield.com',
                'registered_by' => NULL,
                'created' => '2026-01-18 19:47:11',
                'modified' => '2026-01-18 19:47:11',
            ],
        ];

        $table = $this->table('attendees');
        $table->insert($data)->save();
    }
}
