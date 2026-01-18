<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EventsFixture
 */
class EventsFixture extends TestFixture
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
                'uuid' => '28842804-f0ce-48bb-a155-f971f1817722',
                'name' => 'Springfield Marathon',
                'sport' => 'Running',
                'sponsor' => 'Kwik-E-Mart',
                'max_attendees' => 2,
                'date_of_event' => '2026-04-01',
                'location_country_iso' => 'US',
                'status' => 'scheduled',
                'organised_by' => 1,
                'created' => '2026-01-18 15:15:41',
                'modified' => '2026-01-18 15:15:41',
            ],
        ];
        parent::init();
    }
}
