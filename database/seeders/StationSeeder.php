<?php

namespace Database\Seeders;

use Database\Factories\DeviceFactory;
use Database\Factories\StationFactory;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StationFactory::times(20)->create();
    }
}
