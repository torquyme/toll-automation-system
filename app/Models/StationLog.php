<?php

namespace App\Models;

use App\Types\StationLogStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $station_id
 * @property int $device_id
 * @property int $status
 */
class StationLog extends Model
{
    protected $table = 'stations_logs';

    /**
     * Get station
     *
     * @return HasOne
     */
    public function station(): HasOne
    {
        return $this->hasOne(Station::class, 'id', 'station_id');
    }

    /**
     * Get device
     *
     * @return HasOne
     */
    public function device(): HasOne
    {
        return $this->hasOne(Device::class, 'id', 'device_id');
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'device' => [
                'id' => $this->device->id,
                'userId' => $this->device->user_id
            ],
            'station' => [
                'id' => $this->station->id,
                'name' => $this->station->name
            ],
            'action' => StationLogStatus::mapToText($this->status)
        ];
    }
}
