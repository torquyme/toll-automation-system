<?php

namespace App\Services;

use App\Models\Path;
use Illuminate\Support\Collection;

/**
 * PathService
 */
class PathService
{
    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Path::all();
    }
}
