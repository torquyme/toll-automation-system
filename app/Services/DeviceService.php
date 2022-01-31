<?php

namespace App\Services;

use App\Models\Device;
use App\Models\StationLog;
use App\Types\DeviceStatus;
use App\Types\StationLogStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * DeviceService
 */
class DeviceService
{
    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Device::all();
    }

    /**
     * @param int $deviceId
     * @return Device
     * @throws ModelNotFoundException
     */
    public function get(int $deviceId): Device
    {
        return Device::findOrFail($deviceId);
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function getByUserId(int $userId): Collection
    {
        return Device::where(['user_id' => $userId])
            ->whereIn('status', [DeviceStatus::STANDBY, DeviceStatus::IN_MOTORWAY])
            ->get();
    }

    /**
     * @param int $userId
     * @param int $status
     * @return Device
     */
    public function create(int $userId, int $status): Device
    {
        $device = new Device();
        $device->user_id = $userId;
        $device->status = $status;
        $device->save();

        return $device;
    }

    /**
     * @param int $deviceId
     * @return Collection
     */
    public function getLogsByDeviceId(int $deviceId): Collection
    {
        return StationLog::with(['station', 'device'])
            ->where('device_id', $deviceId)
            ->orderBy('created_at')
            ->get();
    }

    /**
     * @param int $deviceId
     * @return Collection
     */
    public function getLogsToBeProcessed(int $deviceId): Collection
    {
        return StationLog::with(['station', 'device'])
            ->where('device_id', $deviceId)
            ->where('status', StationLogStatus::NOT_PROCESSED)
            ->orderBy('created_at')
            ->get();
    }
}
