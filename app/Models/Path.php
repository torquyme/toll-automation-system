<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $start_station
 * @property int $end_station
 * @property float $cost
 */
class Path extends Model
{
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $casts = [
        'cost' => 'float', 'length' => 'float'
    ];

    /**
     * @return int
     */
    public function getStartStationId(): int
    {
        return $this->start_station;
    }

    /**
     * @param int $id
     * @return Path
     */
    public function setStartStationId(int $id): Path
    {
        $this->start_station = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getEndStationId(): int
    {
        return $this->end_station;
    }

    /**
     * @param int $id
     * @return Path
     */
    public function setEndStationId(int $id): Path
    {
        $this->end_station = $id;
        return $this;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     * @return Path
     */
    public function setCost(float $cost): Path
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * Get start station
     *
     * @return HasOne
     */
    public function startStation(): HasOne
    {
        return $this->hasOne(Station::class, 'id', 'start_station');
    }

    /**
     * Get end station
     *
     * @return HasOne
     */
    public function endStation(): HasOne
    {
        return $this->hasOne(Station::class, 'id', 'end_station');
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'startStation' => $this->startStation,
            'endStation' => $this->endStation,
            'cost' => $this->cost
        ];
    }
}
