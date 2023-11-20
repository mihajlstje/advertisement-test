<?php

namespace App\DTO\Category;

use App\Http\Requests\Category\CategoryListRequest;

class CategoryListDTO {

    public function __construct(
        public readonly ?string $sortBy,
        public readonly ?string $sortType,
        public readonly ?int $limit,
        public readonly ?int $page
    )
    {
        
    }

    public static function fromRequest(CategoryListRequest $request){

        return new static(
            $request->validated('sortBy'),
            $request->validated('sortType'),
            $request->validated('limit'),
            $request->validated('page'),
        );

    }
}