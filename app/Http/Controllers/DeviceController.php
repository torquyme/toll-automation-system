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
 *
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
     */
    public function all(): Collection
    {
        return Device::all();
    }

    /**
     * @param Request $request
     * @return Device
     * @throws ValidationException
     */
    public function find(Request $request): Device
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return Device::findOrFail($data['id']);
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
                'userId' => 'string|required|exists:users,id',
                'status' => Rule::in([DeviceStatus::DISABLED, DeviceStatus::STANDBY])
            ]
        );

        return $this->deviceService->create($data['userId'], $data['status']);
    }


    /**
     * @param Request $request
     * @return Collection
     * @throws ValidationException
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
