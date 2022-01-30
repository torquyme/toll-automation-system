<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(StationSeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(PathSeeder::class);
        $this->call(StationLogSeeder::class);
    }
}
