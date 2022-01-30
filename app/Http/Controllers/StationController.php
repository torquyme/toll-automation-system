<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Services\StationService;
use App\Types\StationLogAction;
use App\Types\StationLogStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * StationController
 */
class StationController extends Controller
{
    /**
     * @var StationService
     */
    private StationService $stationService;

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
     * @throws ValidationException
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
     * @throws ValidationException
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
     * @throws ValidationException
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

        $this->stationService->enter($data['stationId'], $data['deviceId']);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function driveThrough(Request $request): JsonResponse
    {
        $data = $this->validate(
            $request,
            [
                'deviceId' => 'int|required|exists:devices,id',
                'stationId' => 'int|required|exists:stations,id'
            ]
        );

        $this->stationService->driveThrough($data['stationId'], $data['deviceId']);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
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

        $this->stationService->exit($data['stationId'], $data['deviceId']);

        return response()->json($data);
    }
}
