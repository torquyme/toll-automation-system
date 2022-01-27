<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Services\StationService;
use App\Types\StationLogStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StationController extends Controller
{
    /**
     * @var StationService
     */
    private $stationService;

    /**
     * @param StationService $stationService
     */
    public function __construct(StationService $stationService)
    {
        $this->stationService = $stationService;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Station::all();
    }

    /**
     * @param Request $request
     * @return Station
     * @throws \Illuminate\Validation\ValidationException
     */
    public function find(Request $request): Station
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return Station::find($data['id']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->validate(
            $request,
            [
                'name' => 'string|required|unique:stations'
            ]
        );

        $station = new Station();
        $station->name = $data['name'];
        $station->save();

        return response()->json($station);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function enter(Request $request): JsonResponse
    {
        $data = $this->validate(
            $request,
            [
                'deviceId' => 'int|required|exists:devices,id',
                'stationId' => 'int|required|exists:stations,id'
            ]
        );

        $this->stationService->logDevice($data['stationId'], $data['deviceId'], StationLogStatus::ENTER);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function exit(Request $request): JsonResponse
    {
        $data = $this->validate(
            $request,
            [
                'deviceId' => 'int|required|exists:devices,id',
                'stationId' => 'int|required|exists:stations,id'
            ]
        );

        $this->stationService->logDevice($data['stationId'], $data['deviceId'], StationLogStatus::EXIT);

        return response()->json($data);
    }
}
