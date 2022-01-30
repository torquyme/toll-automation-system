<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Station;
use App\Models\StationLog;
use App\Types\StationLogAction;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Database\Factories\StationLogFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class StationLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $devices = Device::all();
        $stations = Station::all();

        foreach ($devices as $device) {
            $deviceId = $device->getId();

            $createdAt = Carbon::now();
            for ($travelCount = 0; $travelCount < rand(1, 3); $travelCount++) {
                $randomStations = $stations->random(rand(2, 10));

                //Create enter log
                StationLogFactory::new([
                    'device_id' => $deviceId,
                    'station_id' => $randomStations->pop()->getId(),
                    'action' => StationLogAction::ENTER,
                    'created_at' => $createdAt
                ])->create();

                if ($randomStations->count() > 2) {
                    //Fill with drive through logs
                    StationLogFactory::times($randomStations->count() - 1)->state(
                        new Sequence(function ($sequence) use ($deviceId, $randomStations, $createdAt) {
                            return [
                                'device_id' => $deviceId,
                                'station_id' => $randomStations->pop()->getId(),
                                'created_at' => $createdAt->addMinutes(rand(30, 120)),
                                'action' => StationLogAction::DRIVE_THROUGH
                            ];
                        })
                    )->create();
                }

                //Create exit log
                StationLogFactory::new([
                    'device_id' => $deviceId,
                    'station_id' => $randomStations->pop()->getId(),
                    'action' => StationLogAction::EXIT,
                    'created_at' => $createdAt->addMinutes(rand(30, 120))
                ])->create();
            }
        }
    }
}
