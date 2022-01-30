<?php

namespace Database\Factories;

use App\Models\StationLog;
use App\Types\StationLogStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StationLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => StationLogStatus::NOT_PROCESSED
        ];
    }
}
