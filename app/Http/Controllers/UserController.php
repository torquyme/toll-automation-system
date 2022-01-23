<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{
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

        $user = new User($data);
        $user->save();

        return response()->json($user);
    }
}
