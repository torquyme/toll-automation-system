<?php

namespace App\Services;

use App\Exceptions\InvoiceNotFoundException;
use App\Models\Invoice;
use App\Types\StationLogStatus;
use Illuminate\Support\Carbon;
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

    public function __construct(UserService $userService, DeviceService $deviceService, PathService $pathService) {
        $this->userService = $userService;
        $this->deviceService = $deviceService;
        $this->pathService = $pathService;
    }

    public function calculateMonthlyForUser(int $userId)
    {
        $currentMonth = Carbon::now()->month;

        try {
            $generationMonth = $this->getLastGenerationMonthByUserId($userId);

            if ($currentMonth === $generationMonth) {
                //We've already generated the invoices
                return;
            }
        } catch (InvoiceNotFoundException $e) {
            //No invoices generated for the specified user, continue with generation
        }

        //Get user devices
        $devices = $this->deviceService->getByUserId($userId);

        //Get paths grouped by station id
        $pathsByStationId = $this->pathService->getPaths()->groupBy('start_station');

        foreach ($devices as $device) {
            $logs = $this->deviceService->getDeviceLogs($device->id);
            $routes = $this->routeService->getRoutesFromDeviceLogs($logs);

            $deviceRoutes = [];
            foreach ($routes as $route) {
                $cost = $this->routeService->calculateRouteCost($route, $pathsByStationId);
            }
        }

        //Calculate path cost


        //Loop over each route
        foreach ($groupedRoutes as $deviceId => $routes) {
            $deviceRoutes = [];
            $billAmount = 0;
            foreach ($routes as $route) {

                $visitedStations = $route['routeStations'];
                $completeRoute = ['totalCost' => 0.0, 'paths' => []];

                // We start from the second station
                for ($i = 1; $i < count($visitedStations); $i++) {
                    $stationId = $visitedStations[$i];
                    $previousStationId = $visitedStations[$i - 1];

                    /** @var Collection $paths */
                    $paths = $groupedPath->get($previousStationId);
                    if ($paths === null) {
                        continue;
                    }

                    $path = $paths->where('end_station', $stationId)->first();

                    $completeRoute['paths'][] = $path;
                    $completeRoute['totalCost'] += $path->cost;
                    $billAmount += $path->cost;
                }

                $deviceRoutes[] = $completeRoute;
            }

            $userBill = new Invoice();
            $userBill->user_id = $userId;
            $userBill->device_id = $deviceId;
            $userBill->routes = json_encode($deviceRoutes);
            $userBill->amount = $billAmount;
            $userBill->save();
        }
    }

    public function calculateMonthlyForAllUsers()
    {
        $users = $this->userService->all();
        foreach ($users as $user) {
            $this->calculateMonthlyForUser($user->id);
        }
    }

    /**
     * @throws InvoiceNotFoundException
     */
    public function getLastGenerationMonthByUserId(int $userId): int
    {
        $latest = Invoice::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first('created_at');

        if ($latest === null) {
            throw new InvoiceNotFoundException();
        }

        return Carbon::make($latest->created_at)->month;
    }
}
