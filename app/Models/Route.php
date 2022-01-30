<?php

namespace App\Models;

use JsonSerializable;

/**
 * Route
 */
class Route implements JsonSerializable
{

    /**
     * @var float
     */
    private float $cost = 0.0;

    /**
     * @var Path[]
     */
    private array $paths = [];

    /**
     * @var Station[]
     */
    private array $stations = [];

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     * @return Route
     */
    public function setCost(float $cost): Route
    {
        $this->cost = round($cost, 2);
        return $this;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param Path $path
     *
     * @return Route
     */
    public function addPath(Path $path): Route
    {
        $this->paths[] = $path;
        return $this;
    }

    /**
     * @return Station[]
     */
    public function getStations(): array
    {
        return $this->stations;
    }

    /**
     * @param Station $station
     * @return $this
     */
    public function addStation(Station $station): Route
    {
        $this->stations[] = $station;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return count($this->stations) > 1;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $paths = [];
        foreach ($this->paths as $path) {
            $paths[] = [
                'from' => $path->startStation,
                'to' => $path->endStation,
                'cost' => $path->cost
            ];
        }

        return [
            'cost' => $this->cost,
            'stations' => $this->stations,
            'paths' => $paths
        ];
    }
}
