<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Services\DeviceService;
use App\Services\PathService;
use App\Types\StationLogStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    private $deviceService;
    private $pathService;

    public function __construct(DeviceService $deviceService, PathService $pathService)
    {
        $this->deviceService = $deviceService;
        $this->pathService = $pathService;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return User::all();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function find(Request $request): User
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return User::findOrFail($data['id']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->validate(
            $request,
            ['name' => 'string|required', 'surname' => 'string|required', 'email' => 'email|required|unique:users']
        );

        $user = new User();
        $user->name = $data['name'];
        $user->surname = $data['surname'];
        $user->email = $data['email'];
        $user->save();

        return response()->json($user);
    }

    public function bills(Request $request)
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return Invoice::where('user_id', $data['id'])
            ->get();
    }

}
