<?php

namespace App\Services;

use App\Models\Device;
use App\Models\StationLog;
use App\Types\DeviceStatus;
use App\Types\StationLogStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class DeviceService
{
    /**
     * @param int $deviceId
     *
     * @return Device
     * @throws ModelNotFoundException
     */
    public function get(int $deviceId): Device
    {
        return Device::findOrFail($deviceId);
    }

    public function getByUserId(int $userId): Collection
    {
        return Device::where(['user_id' => $userId])
            ->whereIn('status', [DeviceStatus::STANDBY, DeviceStatus::IN_MOTORWAY])
            ->get();
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
