<?php

namespace App\Models;

class Route
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
     * @var int[]
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
        $this->cost = $cost;
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
     * @return array
     */
    public function getStations(): array
    {
        return $this->stations;
    }

    /**
     * @param int $stationId
     * @return $this
     */
    public function addStation(int $stationId): Route
    {
        $this->stations[] = $stationId;
        return $this;
    }
}
