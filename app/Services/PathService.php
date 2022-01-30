<?php

namespace App\Services;

use App\Models\Path;
use Illuminate\Support\Collection;

/**
 * PathService
 */
class PathService
{
    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Path::all();
    }

    /**
     * @param int $id
     * @return Path
     */
    public function get(int $id): Path
    {
        return Path::find($id);
    }

    /**
     * @param $startStationId
     * @param $endStationId
     * @param $cost
     * @return Collection
     */
    public function create($startStationId, $endStationId, $cost): Collection
    {
        //We assume the path is a two way path, so we insert two path with inverted stations
        $path = (new Path())
            ->setStartStationId($startStationId)
            ->setEndStationId($endStationId)
            ->setCost($cost);
        $path->save();

        $returnPath = (new Path())
            ->setStartStationId($endStationId)
            ->setEndStationId($startStationId)
            ->setCost($cost);
        $returnPath->save();

        return Collection::make([$path, $returnPath]);
    }
}
