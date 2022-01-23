<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Types\DeviceStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
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
     * @throws \Illuminate\Validation\ValidationException
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
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->validate(
            $request,
            [
                'userId' => 'string|required|exists:users,id',
                'status' => Rule::in([DeviceStatus::NOT_ACTIVE, DeviceStatus::ACTIVE])
            ]
        );

        $device = new Device();
        $device->user_id = $data['userId'];
        $device->status = $data['status'];
        $device->save();

        return response()->json(true);
    }
}
