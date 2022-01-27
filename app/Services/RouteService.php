<?php

namespace App\Services;

use App\Models\Path;
use App\Models\Route;
use App\Types\StationLogStatus;
use Illuminate\Support\Collection;

class RouteService
{
    public function calculateRouteCost(Route $route, Collection $pathsByStationId): Route
    {
        // We start from the second station
        for ($i = 1; $i < count($route->getStations()); $i++) {
            $stationId = $route->getStations()[$i];
            $previousStationId = $route->getStations()[$i - 1];

            if ($pathsByStationId->has($previousStationId) === false) {
                continue;
            }

            /** @var Collection $paths */
            $paths = $pathsByStationId->get($previousStationId);

            //Get path identified by route station id and route end station
            /** @var Path $path */
            $path = $paths->where('end_station', $stationId)->first();

            //Add path to route paths
            $route->addPath($path);

            //Set route total cost
            $route->setCost(
                $route->getCost() + $path->getCost()
            );
        }

        return $route;
    }

    /**
     * @param Collection $logs
     * @return Route[]
     */
    public function getRoutesFromDeviceLogs(Collection $logs): array
    {
        $routes = [];
        $route = new Route();
        foreach ($logs as $log) {
            $route->addStation($log->station_id);

            if ($log->status === StationLogStatus::EXIT) {
                $routes[] = $route;

                //Completed the route, clear the route path stations
                $route = new Route();
            }
        }

        return $routes;
    }
}
