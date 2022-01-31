<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Services\DeviceService;
use App\Types\DeviceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * DeviceController
 */
class DeviceController extends Controller
{
    private DeviceService $deviceService;

    /**
     * @param DeviceService $deviceService
     */
    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    /**
     * @return Collection
     *
     * @OA\Get(
     *     path="/api/devices",
     *     summary="Get all the devices",
     *     tags={"Device"},
     *     @OA\Response(response="200", description="Returns all the devices registered in the system")
     * )
     */
    public function all(): Collection
    {
        return $this->deviceService->all();
    }

    /**
     * @param Request $request
     * @return Device
     * @throws ValidationException
     *
     * @OA\Get(
     *     path="/api/device",
     *     summary="Get a device by id",
     *     tags={"Device"},
     *     @OA\Parameter(
     *        name="id",
     *        required=true,
     *        in="query",
     *        description="Device id",
     *        @OA\Schema(
     *          type="integer"
     *        )
     *     ),
     *     @OA\Response(response="200", description="Returns the requested device")
     * )
     */
    public function find(Request $request): Device
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return $this->deviceService->get($data['id']);
    }

    /**
     * @param Request $request
     * @return Device
     * @throws ValidationException
     */
    public function create(Request $request): Device
    {
        $data = $this->validate(
            $request,
            [
                'userId' => 'int|required|exists:users,id',
                'status' => Rule::in([DeviceStatus::DISABLED, DeviceStatus::STANDBY])
            ]
        );

        return $this->deviceService->create($data['userId'], $data['status']);
    }

    /**
     * @param Request $request
     * @return Collection
     * @throws ValidationException
     *
     * @OA\Get(
     *     path="/api/device/logs",
     *     summary="Get logs by device id",
     *     tags={"Device"},
     *     @OA\Parameter(
     *        name="id",
     *        required=true,
     *        in="query",
     *        description="Device id",
     *        @OA\Schema(
     *          type="integer"
     *        )
     *     ),
     *     @OA\Response(response="200", description="Returns the device logs")
     * )
     */
    public function logs(Request $request): Collection
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return $this->deviceService->getLogsByDeviceId($data['id']);
    }
}
