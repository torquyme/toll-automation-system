<?php

namespace App\Services;

use App\Models\Path;
use App\Models\Route;
use App\Models\StationLog;
use App\Types\StationLogStatus;
use App\Types\StationLogAction;
use Illuminate\Support\Collection;

class RouteServiceTest extends \TestCase
{

    public function testGetRoutesFromDeviceLogs()
    {
        /** @var RouteService $routeService */
        $routeService = app(RouteService::class);

        $enterStationLog = new StationLog();
        $enterStationLog->station_id = 1;
        $enterStationLog->device_id = 1;
        $enterStationLog->status = StationLogAction::ENTER;

        $passedByStationLog = new StationLog();
        $passedByStationLog->station_id = 2;
        $passedByStationLog->device_id = 1;
        $passedByStationLog->status = StationLogAction::PASSED_BY;

        $exitStationLog = new StationLog();
        $exitStationLog->station_id = 3;
        $exitStationLog->device_id = 1;
        $exitStationLog->status = StationLogAction::EXIT;

        $routes = $routeService->parseRoutesFromLogs(Collection::make([$enterStationLog, $passedByStationLog, $exitStationLog]));
        $this->assertNotEmpty($routes);
        $this->assertCount(1, $routes);

        $route = array_pop($routes);
        $this->assertCount(3, $route->getStations());
        $this->assertEquals(1, $route->getStations()[0]);
        $this->assertEquals(2, $route->getStations()[1]);
        $this->assertEquals(3, $route->getStations()[2]);
    }

    public function testGetRoutesFromDeviceLogsNoLogs()
    {
        /** @var RouteService $routeService */
        $routeService = app(RouteService::class);

        $routes = $routeService->parseRoutesFromLogs(Collection::make([]));
        $this->assertEmpty($routes);
    }

    public function testCalculateRouteCost()
    {
        /** @var RouteService $routeService */
        $routeService = app(RouteService::class);

        $route = new Route();
        $route->addStation(1)->addStation(2)->addStation(3);

        $firstPath = new Path();
        $firstPath->setStartStationId(1);
        $firstPath->setEndStationId(2);
        $firstPath->setCost(120);

        $secondPath = new Path();
        $secondPath->setStartStationId(2);
        $secondPath->setEndStationId(3);
        $secondPath->setCost(100);

        $paths = Collection::make([$firstPath, $secondPath])->groupBy('start_station');

        $route = $routeService->processRoute($route, $paths);
        $this->assertCount(2, $route->getPaths());
        $this->assertEquals(220, $route->getCost());
    }
}
