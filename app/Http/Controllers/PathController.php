<?php

namespace App\Http\Controllers;

use App\Models\Path;
use App\Services\PathService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * PathController
 */
class PathController extends Controller
{
    /**
     * @var PathService
     */
    private PathService $pathService;

    /**
     * @param PathService $pathService
     */
    public function __construct(PathService $pathService)
    {
        $this->pathService = $pathService;
    }

    /**
     * @return Collection
     *
     * @OA\Get(
     *     path="/api/paths",
     *     summary="Get all the paths",
     *     tags={"Path"},
     *     @OA\Response(response="200", description="Returns all the paths registered in the system")
     * )
     */
    public function all(): Collection
    {
        return $this->pathService->all();
    }

    /**
     * @param Request $request
     * @return Path
     * @throws ValidationException
     *
     * @OA\Get(
     *     path="/api/path",
     *     summary="Get a path by id",
     *     tags={"Path"},
     *     @OA\Parameter(
     *        name="id",
     *        required=true,
     *        in="query",
     *        description="Path id",
     *        @OA\Schema(
     *          type="integer"
     *        )
     *     ),
     *     @OA\Response(response="200", description="Returns the requested path")
     * )
     */
    public function find(Request $request): Path
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return $this->pathService->get($data['id']);
    }

    /**
     * @param Request $request
     * @return Collection
     * @throws ValidationException
     */
    public function create(Request $request): Collection
    {
        $data = $this->validate(
            $request,
            [
                'startStationId' => 'int|required|exists:stations,id',
                'endStationId' => 'int|required|exists:stations,id|different:startStationId',
                'cost' => 'numeric|required|min:1.0|max:99.0'
            ]
        );

        return $this->pathService->create(
            $data['startStationId'], $data['endStationId'], $data['cost']
        );
    }
}
