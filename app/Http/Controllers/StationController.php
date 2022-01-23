<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StationController extends Controller
{
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
     * @throws \Illuminate\Validation\ValidationException
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
     * @throws \Illuminate\Validation\ValidationException
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
}
