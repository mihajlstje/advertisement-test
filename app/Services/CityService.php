<?php

namespace App\Services;

use App\Interfaces\CityServiceInterface;
use App\Models\City;
use Illuminate\Support\Collection;

class CityService implements CityServiceInterface {

    public function all(): Collection
    {
        return City::all();
    }

}