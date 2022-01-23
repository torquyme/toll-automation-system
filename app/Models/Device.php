<?php

namespace App\Models;

use App\Types\DeviceStatus;
use Illuminate\Database\Eloquent\Model;

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
