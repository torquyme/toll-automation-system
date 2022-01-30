<?php

namespace App\Services;

use App\Models\Station;
use App\Models\StationLog;
use App\Types\DeviceStatus;
use App\Types\StationLogAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StationService
{
    private DeviceService $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function all(): Collection
    {
        return Station::all();
    }

    public function enter(int $stationId, int $deviceId)
    {
        //Get device status
        $deviceStatus = $this->deviceService->get($deviceId)->getStatus();

        //Validate status is STANDBY
        Validator::validate(
            ['status' => $deviceStatus],
            ['status' => Rule::in([DeviceStatus::DISABLED, DeviceStatus::STANDBY])],
            ['status' => 'The device is disabled or it already entered the motorway']
        );

        //Log motorway enter
        $this->logDevice($stationId, $deviceId, StationLogAction::ENTER);
    }

    public function driveThrough(int $stationId, int $deviceId)
    {
        //Get device status
        $deviceStatus = $this->deviceService->get($deviceId)->getStatus();

        //Validate status is IN_MOTORWAY
        Validator::validate(
            ['status' => $deviceStatus],
            ['status' => Rule::in([DeviceStatus::DISABLED, DeviceStatus::IN_MOTORWAY])],
            ['status' => 'The device is disabled or not inside the motorway']
        );

        //Log motorway drive through
        $this->logDevice($stationId, $deviceId, StationLogAction::ENTER);

    }

    public function exit(int $stationId, int $deviceId)
    {
        //Get device status
        $deviceStatus = $this->deviceService->get($deviceId)->getStatus();

        //Validate status is IN_MOTORWAY
        Validator::validate(
            ['status' => $deviceStatus],
            ['status' => Rule::in([DeviceStatus::DISABLED, DeviceStatus::IN_MOTORWAY])],
            ['status' => 'The device is disabled or not inside the motorway']
        );

        //Log motorway exit
        $this->logDevice($stationId, $deviceId, StationLogAction::ENTER);
    }

    public function logDevice(int $stationId, int $deviceId, int $status)
    {
        $entry = new StationLog();
        $entry->station_id = $stationId;
        $entry->device_id = $deviceId;
        $entry->status = $status;

        $entry->save();
    }
}
