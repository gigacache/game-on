<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $token_hash
 * @property \Cake\I18n\DateTime $token_created_at
 * @property bool $is_token_active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $updated
 */
class User extends Entity
{
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
        'first_name' => true,
        'last_name' => true,
        'email' => true,
        'token_hash' => true,
        'token_created_at' => true,
        'is_token_active' => true,
        'created' => true,
        'updated' => true,
    ];

    protected array $_virtual = ['full_name'];

    /**
     * Get full_name virtual field
     *
     * Combines first_name and last_name into a single string.
     *
     * @return string|null Full name of the user
     */
    protected function _getFullName(): ?string
    {
        if (!empty($this->first_name) || !empty($this->last_name)) {
            return trim($this->first_name . ' ' . $this->last_name);
        }

        return null;
    }
}
