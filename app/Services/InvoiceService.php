<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Invoice;
use App\Models\Route;
use App\Models\Station;
use App\Models\StationLog;
use App\Types\StationLogStatus;
use Illuminate\Support\Collection;

class InvoiceService
{
    /**
     * @var UserService
     */
    private UserService $userService;
    private DeviceService $deviceService;
    private PathService $pathService;
    private RouteService $routeService;
    private StationService $stationService;

    public function __construct(
        UserService $userService,
        DeviceService $deviceService,
        PathService $pathService,
        RouteService $routeService,
        StationService $stationService
    ) {
        $this->userService = $userService;
        $this->deviceService = $deviceService;
        $this->pathService = $pathService;
        $this->routeService = $routeService;
        $this->stationService = $stationService;
    }

    public function getByUserId(int $userId): Collection
    {
        return Invoice::where('user_id', $userId)
            ->get();
    }

    /**
     * @throws \App\Exceptions\PathNotFoundException
     */
    public function calculateMonthlyForUser(int $userId)
    {
        //Get user devices
        $devices = $this->deviceService->getByUserId($userId);

        //Get paths
        $paths = $this->pathService->all();

        /** @var Device $device */
        foreach ($devices as $device) {
            $logs = $this->deviceService->getLogs($device->getId());
            $routes = $this->routeService->parseRoutesFromLogs($logs);

            //No routes have been driven, continue to next device
            if (empty($routes) === true) {
                continue;
            }

            //Loop over routes and calculate the cost
            foreach ($routes as $route) {
                $this->routeService->processRoute($route, $paths);
            }

            //Create invoice in database
            $this->createInvoice($userId, $device->getId(), $routes);

            //Set logs as processed
            $logs->each(function(StationLog $log) {
                $log->update(['status' => StationLogStatus::PROCESSED]);
            });
        }
    }

    public function createInvoice(int $userId, int $deviceId, array $routes)
    {
        //Calculate total amount
        $amount = array_reduce($routes, function (float $total, Route $route) {
           return $total + $route->getCost();
        }, 0.0);

        //Convert routes array to string
        $routes = json_encode($routes);

        //Create invoice and save it
        $invoice = new Invoice();
        $invoice->setUserId($userId)
            ->setDeviceId($deviceId)
            ->setRoutes($routes)
            ->setAmount($amount)
            ->save();
    }

    public function calculateMonthlyForAllUsers()
    {
        $users = $this->userService->all();
        foreach ($users as $user) {
            $this->calculateMonthlyForUser($user->id);
        }
    }
}
