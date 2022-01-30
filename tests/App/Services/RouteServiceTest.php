<?php

namespace App\Services;

use App\Exceptions\PathNotFoundException;
use App\Models\Path;
use App\Models\Route;
use App\Models\Station;
use App\Models\StationLog;
use App\Types\StationLogStatus;
use App\Types\StationLogAction;
use Illuminate\Support\Collection;

class RouteServiceTest extends \TestCase
{

    public function testParseRoutesFromLogs()
    {
        /** @var RouteService $routeService */
        $routeService = app(RouteService::class);

        $enterStationLog = new StationLog();
        $enterStationLog->station_id = 1;
        $enterStationLog->device_id = 1;
        $enterStationLog->action = StationLogAction::ENTER;

        $passedByStationLog = new StationLog();
        $passedByStationLog->station_id = 2;
        $passedByStationLog->device_id = 1;
        $passedByStationLog->action = StationLogAction::DRIVE_THROUGH;

        $exitStationLog = new StationLog();
        $exitStationLog->station_id = 3;
        $exitStationLog->device_id = 1;
        $exitStationLog->action = StationLogAction::EXIT;

        $routes = $routeService->parseRoutesFromLogs(Collection::make([$enterStationLog, $passedByStationLog, $exitStationLog]));
        $this->assertNotEmpty($routes);
        $this->assertCount(1, $routes);

        $route = array_pop($routes);
        $this->assertCount(3, $route->getStations());
        $this->assertEquals(1, $route->getStations()[0]->getId());
        $this->assertEquals(2, $route->getStations()[1]->getId());
        $this->assertEquals(3, $route->getStations()[2]->getId());
    }

    public function testParseRoutesFromLogsEmptyLogs()
    {
        /** @var RouteService $routeService */
        $routeService = app(RouteService::class);

        $routes = $routeService->parseRoutesFromLogs(Collection::make([]));
        $this->assertEmpty($routes);
    }

    public function testParseRoutesFromLogsOnlyOneLog()
    {
        /** @var RouteService $routeService */
        $routeService = app(RouteService::class);

        $enterStationLog = new StationLog();
        $enterStationLog->station_id = 1;
        $enterStationLog->device_id = 1;
        $enterStationLog->action = StationLogAction::ENTER;

        $routes = $routeService->parseRoutesFromLogs(Collection::make([]));
        $this->assertEmpty($routes);
    }

    public function testCalculateRouteCost()
    {
        /** @var RouteService $routeService */
        $routeService = app(RouteService::class);

        $firstStation = new Station();
        $firstStation->id = 1;

        $secondStation = new Station();
        $secondStation->id = 2;

        $thirdStation = new Station();
        $thirdStation->id = 3;

        $route = new Route();
        $route->addStation($firstStation)->addStation($secondStation)->addStation($thirdStation);

        $firstPath = new Path();
        $firstPath->setStartStationId(1);
        $firstPath->setEndStationId(2);
        $firstPath->setCost(120);

        $secondPath = new Path();
        $secondPath->setStartStationId(2);
        $secondPath->setEndStationId(3);
        $secondPath->setCost(100);

        $paths = Collection::make([$firstPath, $secondPath]);

        $routeService->processRoute($route, $paths);
        $this->assertCount(2, $route->getPaths());
        $this->assertEquals(220, $route->getCost());
    }

    public function testCalculateRouteCostNoPathFound()
    {
        /** @var RouteService $routeService */
        $routeService = app(RouteService::class);

        $firstStation = new Station();
        $firstStation->id = 1;

        $secondStation = new Station();
        $secondStation->id = 2;

        $route = new Route();
        $route->addStation($firstStation)->addStation($secondStation);

        $firstPath = new Path();
        $firstPath->setStartStationId(4);
        $firstPath->setEndStationId(3);
        $firstPath->setCost(120);

        $secondPath = new Path();
        $secondPath->setStartStationId(4);
        $secondPath->setEndStationId(5);
        $secondPath->setCost(100);

        $paths = Collection::make([$firstPath, $secondPath]);

        $this->expectException(PathNotFoundException::class);
        $routeService->processRoute($route, $paths);
    }
}
