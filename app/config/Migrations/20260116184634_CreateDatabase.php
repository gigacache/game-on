<?php

declare(strict_types=1);

use Migrations\BaseMigration;

class CreateDatabase extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('users');
        $table
            ->addColumn('uuid', 'string', ['limit' => 36, 'null' => false])
            ->addIndex(['uuid'], ['unique' => true])
            ->addColumn('first_name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('last_name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 150, 'null' => false])
            ->addIndex(['email'], ['unique' => true])
            ->addColumn('token_hash', 'string', ['limit' => 64, 'null' => false])
            ->addIndex(['token_hash'], ['unique' => true])
            ->addColumn('token_created_at', 'datetime', ['null' => false])
            ->addColumn('is_token_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', [
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('updated', 'datetime', [
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP'
            ]);
        $table->create();


        $table = $this->table('events');
        $table
            ->addColumn('uuid', 'string', ['limit' => 36, 'null' => false])
            ->addIndex(['uuid'], ['unique' => true])
            ->addColumn('name', 'string', ['limit' => 200, 'null' => false])
            ->addColumn('sport', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('sponsor', 'string', ['limit' => 150, 'null' => false])
            ->addColumn('max_attendees', 'integer', ['null' => false])
            ->addColumn('date_of_event', 'date', ['null' => true, 'default' => null])
            ->addColumn('location_country_iso', 'string', ['limit' => 3, 'null' => false,])
            ->addColumn('status', 'string', [
                'limit' => 20,
                'default' => 'draft',
            ])
            ->addColumn('organised_by', 'integer', ['null' => true])
            ->addIndex(['organised_by'])
            ->addColumn('created', 'datetime', [
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('updated', 'datetime', [
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->addForeignKey('organised_by', 'users', 'id');
        $table->create();


        $table = $this->table('attendees');
        $table
            ->addColumn('uuid', 'string', ['limit' => 36, 'null' => false])
            ->addIndex(['uuid'], ['unique' => true])
            ->addColumn('first_name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('last_name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('mobile', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 150, 'null' => false])
            ->addColumn('registered_by', 'integer', ['null' => true])
            ->addIndex(['registered_by'])
            ->addColumn('created', 'datetime', [
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('updated', 'datetime', [
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->addForeignKey('registered_by', 'users', 'id');
        $table->create();


        $table = $this->table('bookings');
        $table
            ->addColumn('uuid', 'string', ['limit' => 36, 'null' => false])
            ->addIndex(['uuid'], ['unique' => true])
            ->addColumn('event_id', 'integer', ['null' => false])
            ->addIndex(['event_id'], ['unique' => true])
            ->addColumn('attendee_id', 'integer', ['null' => false])
            ->addIndex(['attendee_id'], ['unique' => true])
            ->addColumn('booked_by', 'integer', ['null' => false])
            ->addIndex(['booked_by'])
            ->addColumn('created', 'datetime', [
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('updated', 'datetime', [
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->addForeignKey('booked_by', 'users', 'id')
            ->addForeignKey('event_id', 'events', 'id')
            ->addForeignKey('attendee_id', 'attendees', 'id');
        $table->create();
    }
}
