<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\InvoiceService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    private UserService $userService;
    private InvoiceService $invoiceService;

    public function __construct(UserService $userService, InvoiceService $invoiceService)
    {
        $this->userService = $userService;
        $this->invoiceService = $invoiceService;
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

        $user = $this->userService->create($data['name'], $data['surname'], $data['email']);

        return response()->json($user);
    }

    public function invoices(Request $request)
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return $this->invoiceService->getByUserId($data['id']);
    }

}
