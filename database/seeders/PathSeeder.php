<?php

namespace Database\Seeders;

use App\Models\Station;
use Database\Factories\PathFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stations = Station::all();

        $paths = [];
        for($i = 0; $i < count($stations); $i++) {
            for ($j = 0; $j < count($stations); $j++) {
                $startStationId = $stations[$i]->getId();
                $endStationId = $stations[$j]->getId();
                if ($startStationId === $endStationId) {
                    continue;
                }

                PathFactory::new(['start_station' => $startStationId, 'end_station' => $endStationId])->create();
            }
        }
    }
}
