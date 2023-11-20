<?php

namespace App\DTO\Advert;

use App\Http\Requests\Advert\AdvertListRequest;

class AdvertListDTO {

    public function __construct(
        public readonly ?string $sortBy,
        public readonly ?string $sortType,
        public readonly ?int $limit,
        public readonly ?int $page,
        public readonly ?string $keyword,
        public readonly ?int $category_id,
        public readonly ?int $city_id,
        public readonly ?int $minPrice,
        public readonly ?int $maxPrice
    )
    {

    }

    public static function factory(?array $data){

        return new static(
            $data['sortBy'] ?? null,
            $data['sortType'] ?? null,
            $data['limit'] ?? null,
            $data['page'] ?? null,
            $data['keyword'] ?? null,
            $data['category_id'] ?? null,
            $data['city_id'] ?? null,
            $data['minPrice'] ?? null,
            $data['maxPrice'] ?? null,
        );
    
    }

    public static function fromRequest(AdvertListRequest $request): self
    {

        return new static(
            $request->validated('sortBy'),
            $request->validated('sortType'),
            $request->validated('limit'),
            $request->validated('page'),
            $request->validated('keyword'),
            $request->validated('category_id'),
            $request->validated('city_id'),
            $request->validated('minPrice'),
            $request->validated('maxPrice'),

        );

    }
}