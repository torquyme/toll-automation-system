<?php

namespace App\Services;

use App\Models\Path;
use Illuminate\Support\Collection;

class PathService
{
    public function getPaths(): Collection
    {
        return Path::all();
    }
}
