<?php

namespace suplascripts\models\voice;

use Assert\Assertion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use suplascripts\models\Model;
use suplascripts\models\User;

/**
 * @property string $label
 * @property bool $enabled
 * @property string $slug
 * @property mixed $roomsState
 * @property mixed $devicesState
 * @property \DateTime $nextProfileChange
 * @property User $user
 */
class VoiceCommand extends Model
{
    const TABLE_NAME = 'voice_commands';
    const TRIGGERS = 'triggers';
    const SCENE = 'scene';
    const FEEDBACK = 'feedback';
    const LAST_USED = 'lastUsed';
    const USER_ID = 'userId';

    protected $dates = [self::LAST_USED];
    protected $fillable = [self::TRIGGERS, self::SCENE, self::FEEDBACK];
    protected $jsonEncoded = [self::TRIGGERS];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }

    public function log($data)
    {
        $this->user()->first()->log('voice', $data, $this->id);
    }

    public function validate(array $attributes = null)
    {
        if (!$attributes) {
            $attributes = $this->getAttributes();
        }
        Assertion::notEmptyKey($attributes, self::TRIGGERS, 'Voice command must have at least one trigger.');
        Assertion::isArray($attributes[self::TRIGGERS]);
        Assertion::greaterThan(count(array_filter($attributes[self::TRIGGERS])), 0, 'Voice command must have at least one trigger.');
    }
}
