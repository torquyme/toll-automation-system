<?php

namespace App\Services;

use App\Models\Device;
use App\Models\StationLog;
use Illuminate\Support\Collection;

class DeviceService
{
    public function getByUserId(int $userId): Collection
    {
        return Device::where('user_id', $userId)
            ->get();
    }

    public function getDeviceLogs(int $deviceId): Collection
    {
        return StationLog::with(['station', 'device'])
            ->where('device_id', $deviceId)
            ->orderBy('created_at')
            ->get();
    }
}
