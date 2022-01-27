<?php

namespace App\Services;

use App\Models\StationLog;

class StationService
{
    /**
     * @param int $stationId
     * @param int $deviceId
     * @param int $status
     *
     * @return void
     */
    public function logDevice(int $stationId, int $deviceId, int $status)
    {
        $entry = new StationLog();
        $entry->station_id = $stationId;
        $entry->device_id = $deviceId;
        $entry->status = $status;

        $entry->save();
    }
}
