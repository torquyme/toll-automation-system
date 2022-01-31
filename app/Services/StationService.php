<?php

namespace App\Services;

use App\Models\Station;
use App\Models\StationLog;
use App\Types\DeviceStatus;
use App\Types\StationLogAction;
use App\Types\StationLogStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * StationService
 */
class StationService
{
    /**
     * @var DeviceService
     */
    private DeviceService $deviceService;

    /**
     * @param DeviceService $deviceService
     */
    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Station::all();
    }

    /**
     * @param int $stationId
     * @return Station
     */
    public function get(int $stationId): Station
    {
        return Station::find($stationId);
    }

    /**
     * @param int $stationId
     * @param int $deviceId
     * @return void
     * @throws \Exception
     */
    public function enter(int $stationId, int $deviceId)
    {
        //Get device status
        $device = $this->deviceService->get($deviceId);
        $deviceStatus = $device->getStatus();

        //Validate status is STANDBY
        Validator::validate(
            ['status' => $deviceStatus],
            ['status' => Rule::in([DeviceStatus::DISABLED, DeviceStatus::STANDBY])],
            ['status' => 'The device is disabled or it already entered the motorway']
        );

        //Log motorway enter
        $this->createLog($stationId, $deviceId, StationLogAction::ENTER);

        DB::beginTransaction();
        try {
            //Log motorway drive through
            $this->createLog($stationId, $deviceId, StationLogAction::ENTER);

            //Update device status
            $device->setStatus(DeviceStatus::IN_MOTORWAY);
            $device->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $stationId
     * @param int $deviceId
     * @return void
     * @throws \Exception
     */
    public function driveThrough(int $stationId, int $deviceId)
    {
        //Get device status
        $device = $this->deviceService->get($deviceId);
        $deviceStatus = $device->getStatus();

        //Validate status is IN_MOTORWAY
        Validator::validate(
            ['status' => $deviceStatus],
            ['status' => Rule::in([DeviceStatus::DISABLED, DeviceStatus::IN_MOTORWAY])],
            ['status' => 'The device is disabled or not inside the motorway']
        );

        //Log motorway drive through
        $this->createLog($stationId, $deviceId, StationLogAction::DRIVE_THROUGH);
    }

    /**
     * @param int $stationId
     * @param int $deviceId
     * @return void
     * @throws \Exception
     */
    public function exit(int $stationId, int $deviceId)
    {
        //Get device status
        $device = $this->deviceService->get($deviceId);
        $deviceStatus = $device->getStatus();

        //Validate status is IN_MOTORWAY
        Validator::validate(
            ['status' => $deviceStatus],
            ['status' => Rule::in([DeviceStatus::DISABLED, DeviceStatus::IN_MOTORWAY])],
            ['status' => 'The device is disabled or not inside the motorway']
        );

        DB::beginTransaction();
        try {
            //Log motorway drive through
            $this->createLog($stationId, $deviceId, StationLogAction::EXIT);

            //Update device status
            $device->setStatus(DeviceStatus::STANDBY);
            $device->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $stationId
     * @param int $deviceId
     * @param int $action
     * @return void
     */
    private function createLog(int $stationId, int $deviceId, int $action)
    {
        $entry = (new StationLog())
            ->setStationId($stationId)
            ->setDeviceId($deviceId)
            ->setAction($action)
            ->setStatus(StationLogStatus::NOT_PROCESSED);

        $entry->save();
    }
}
