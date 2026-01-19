<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Bookings seed.
 */
class BookingsSeed extends BaseSeed
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
                'uuid' => '65ce544f-0def-4291-810a-711849a2e09b',
                'event_id' => 1,
                'attendee_id' => 1,
                'booked_by' => 1,
                'created' => '2026-01-19 18:06:24',
                'modified' => '2026-01-19 18:06:24',
            ],
        ];

        $table = $this->table('bookings');
        $table->insert($data)->save();
    }
}
