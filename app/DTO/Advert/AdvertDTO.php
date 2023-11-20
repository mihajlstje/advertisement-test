<?php

namespace App\DTO\Advert;

use App\Http\Requests\Advert\AdvertRequest;
use Illuminate\Http\UploadedFile;

class AdvertDTO {

    public function __construct(
        public readonly string $title,
        public readonly string $desc,
        public readonly int $category_id,
        public readonly int $city_id,
        public readonly float $price,
        public readonly int $condition,
        public readonly string $phone,
        public readonly ?UploadedFile $image
    )
    {
        
    }

    public static function fromRequest(AdvertRequest $request): self
    {
        return new static(
            $request->validated('title'),
            $request->validated('desc'),
            $request->validated('category_id'),
            $request->validated('city_id'),
            $request->validated('price'),
            $request->validated('condition'),
            $request->validated('phone'),
            $request->validated('image')
        );
    }

}