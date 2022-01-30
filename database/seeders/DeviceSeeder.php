<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\DeviceFactory;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            DeviceFactory::times(rand(1, 3))
                ->state(['user_id' => $user->getId()])
                ->create();
        }
    }
}
