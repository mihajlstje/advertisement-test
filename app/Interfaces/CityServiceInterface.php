<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface CityServiceInterface {

    public function all(): Collection;

}