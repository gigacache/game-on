<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BookingsFixture
 */
class BookingsFixture extends TestFixture
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
                'uuid' => '65ce544f-0def-4291-810a-711849a2e09b',
                'event_id' => 1,
                'attendee_id' => 1,
                'booked_by' => 1,
                'created' => '2026-01-19 18:06:24',
                'modified' => '2026-01-19 18:06:24',
            ],
        ];
        parent::init();
    }
}
