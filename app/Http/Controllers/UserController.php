<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\InvoiceService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * UserController
 */
class UserController extends Controller
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @var InvoiceService
     */
    private InvoiceService $invoiceService;

    /**
     * @param UserService $userService
     * @param InvoiceService $invoiceService
     */
    public function __construct(UserService $userService, InvoiceService $invoiceService)
    {
        $this->userService = $userService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * @return Collection
     *
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all the users",
     *     tags={"User"},
     *     @OA\Response(response="200", description="Returns all the users registered in the system")
     * )
     */
    public function all(): Collection
    {
        return $this->userService->all();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     *
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get a user by id",
     *     tags={"User"},
     *     @OA\Parameter(
     *        name="id",
     *        required=true,
     *        in="query",
     *        description="User id",
     *        @OA\Schema(
     *          type="integer"
     *        )
     *     ),
     *     @OA\Response(response="200", description="Returns the requested user")
     * )
     */
    public function find(Request $request): User
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return $this->userService->get($data['id']);
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
            ['name' => 'string|required', 'surname' => 'string|required', 'email' => 'email|required|unique:users']
        );

        $user = $this->userService->create($data['name'], $data['surname'], $data['email']);

        return response()->json($user);
    }

    /**
     * @param Request $request
     * @return Collection
     * @throws ValidationException
     */
    public function invoices(Request $request): Collection
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return $this->invoiceService->getByUserId($data['id']);
    }

}
