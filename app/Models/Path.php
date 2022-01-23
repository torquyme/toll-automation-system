<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property Station $startStation
 * @property Station $endStation
 * @property float $cost
 * @property float $length
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
     * @return HasOne
     */
    public function startStation(): HasOne
    {
        return $this->hasOne(Station::class, 'id', 'start_station');
    }

    /**
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
            'cost' => $this->cost,
            'length' => $this->length,
            'totalCost' => $this->cost * $this->length
        ];
    }
}
