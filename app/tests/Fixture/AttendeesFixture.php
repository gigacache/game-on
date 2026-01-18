<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AttendeesFixture
 */
class AttendeesFixture extends TestFixture
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
                'uuid' => 'Lorem ipsum dolor sit amet',
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'mobile' => 'Lorem ipsum dolor ',
                'email' => 'Lorem ipsum dolor sit amet',
                'registered_by' => 1,
                'created' => '2026-01-18 19:29:30',
                'modified' => '2026-01-18 19:29:30',
            ],
        ];
        parent::init();
    }
}
