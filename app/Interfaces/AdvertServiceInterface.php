<?php

namespace App\Interfaces;

use App\DTO\Advert\AdvertDTO;
use App\DTO\Advert\AdvertListDTO;
use App\Models\Advert;
use Illuminate\Pagination\LengthAwarePaginator;

interface AdvertServiceInterface {

    public function index(AdvertListDTO $dto, ?int $user_id = null): LengthAwarePaginator;

    public function create(AdvertDTO $dto): Advert;

    public function update(Advert $advert, AdvertDTO $dto): Advert;

    public function delete(Advert $advert): bool;

}