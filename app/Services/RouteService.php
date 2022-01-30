<?php

namespace App\Services;

use App\Exceptions\PathNotFoundException;
use App\Models\Path;
use App\Models\Route;
use App\Models\StationLog;
use App\Types\StationLogAction;
use Illuminate\Support\Collection;

/**
 * RouteService
 */
class RouteService
{
    /**
     * @throws PathNotFoundException
     */
    public function processRoute(Route &$route, Collection $paths)
    {
        //Two way paths
        $groupedByStartStation = $paths->groupBy('start_station');

        // We start from the second station
        for ($i = 1; $i < count($route->getStations()); $i++) {
            $stationId = $route->getStations()[$i]->getId();
            $previousStationId = $route->getStations()[$i - 1]->getId();

            if ($groupedByStartStation->has($previousStationId) === false) {
                throw new PathNotFoundException("Not path found with start station $previousStationId");
            }

            /** @var Collection $paths */
            $paths = $groupedByStartStation->get($previousStationId);

            //Get path identified by route station id and route end station
            /** @var Path $path */
            $path = $paths->where('end_station', $stationId)->first();
            if ($path === null) {
                throw new PathNotFoundException("Not path found with end station $stationId");
            }

            //Add path to route paths
            $route->addPath($path);

            //Set route total cost
            $route->setCost(
                $route->getCost() + $path->getCost()
            );
        }
    }

    /**
     * @param Collection $logs
     * @return Route[]
     */
    public function parseRoutesFromLogs(Collection $logs): array
    {
        $routes = [];
        $route = new Route();

        /** @var StationLog $log */
        foreach ($logs as $log) {
            $route->addStation($log->station);

            if ($log->getAction() === StationLogAction::EXIT) {
                if ($route->isValid()) {
                    $routes[] = $route;
                }

                $route = new Route();
            }
        }

        return $routes;
    }
}
