<?php

namespace App\Http\Controllers;

use App\Models\Path;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class PathController extends Controller
{
    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Path::all();
    }

    /**
     * @param Request $request
     * @return Path
     * @throws \Illuminate\Validation\ValidationException
     */
    public function find(Request $request): Path
    {
        $data = $this->validate(
            $request,
            ['id' => 'int|required']
        );

        return Path::find($data['id']);
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
                'startStationId' => 'int|required|exists:stations,id',
                'endStationId' => 'int|required|exists:stations,id|different:startStationId',
                'cost' => 'numeric|required|min:1.0|max:99.0',
                'length' => 'numeric|min:1.0|max:200.0'
            ]
        );

        $path = new Path();
        $path->start_station = $data['startStationId'];
        $path->end_station = $data['endStationId'];
        $path->cost = $data['cost'];
        $path->length = $data['length'];
        $path->save();

        return response()->json($path);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function enter(Request $request): Response
    {
        $data = $this->validate(
            $request,
            [
                'deviceId' => 'int|required|exists:devices,id',
                'stationId' => 'int|required|exists:stations,id'
            ]
        );


    }
}
