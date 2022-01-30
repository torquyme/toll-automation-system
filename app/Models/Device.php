<?php

namespace App\Models;

use App\Types\DeviceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $user_id
 * @property int $status
 */
class Device extends Model
{
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Device
     */
    public function setId(int $id): Device
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $userId
     * @return Device
     */
    public function setUserId(int $userId): Device
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Device
     */
    public function setStatus(int $status): Device
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'status' => $this->status,
            'statusDescription' => DeviceStatus::mapToText($this->status)
        ];
    }
}
