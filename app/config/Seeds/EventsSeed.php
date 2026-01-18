<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Events seed.
 */
class EventsSeed extends BaseSeed
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
                'uuid' => 'b79d1f65-6e76-403a-a14a-8e3eee6babc0',
                'name' => 'Springfield Marathon',
                'sport' => 'Running1',
                'sponsor' => 'Kwik-E-Mart11',
                'max_attendees' => 5,
                'date_of_event' => '2026-04-01',
                'location_country_iso' => 'US',
                'status' => 'scheduled',
                'organised_by' => 1,
                'created' => '2026-01-18 16:08:38',
                'modified' => '2026-01-18 16:17:55',
            ],
        ];

        $table = $this->table('events');
        $table->insert($data)->save();
    }
}
