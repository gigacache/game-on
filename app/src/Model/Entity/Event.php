<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Event Entity
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $sport
 * @property string $sponsor
 * @property int $max_attendees
 * @property \Cake\I18n\Date|null $date_of_event
 * @property string $location_country_iso
 * @property string $status
 * @property int|null $organised_by
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $updated
 *
 * @property \App\Model\Entity\Booking $booking
 */
class Event extends Entity
{
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'uuid' => true,
        'name' => true,
        'sport' => true,
        'sponsor' => true,
        'max_attendees' => true,
        'date_of_event' => true,
        'location_country_iso' => true,
        'status' => true,
        'organised_by' => true,
        'created' => true,
        'modified' => true,
        'booking' => true,
    ];
}
