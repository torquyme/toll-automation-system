<?php

namespace App\Models;

use App\Types\StationLogAction;
use App\Types\StationLogStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $station_id
 * @property int $device_id
 * @property int $action
 * @property int $status
 * @property Station $station
 * @property Device $device
 */
class StationLog extends Model
{
    protected $table = 'stations_logs';
    protected $fillable = [
        'status'
    ];

    /**
     * @return int
     */
    public function getStationId(): int
    {
        return $this->station_id;
    }

    /**
     * @return int
     */
    public function getDeviceId(): int
    {
        return $this->device_id;
    }

    /**
     * @return int
     */
    public function getAction(): int
    {
        return $this->action;
    }

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

    /**
     * @return array
     */
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
            'action' => StationLogAction::mapToText($this->action),
            'status' => StationLogStatus::mapToText($this->status)
        ];
    }
}
