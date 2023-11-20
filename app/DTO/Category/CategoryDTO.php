<?php

namespace App\DTO\Category;

use App\Http\Requests\Category\CategoryRequest;

class CategoryDTO {

    public function __construct(
        public readonly string $name,
        public readonly ?int $parent_id
    )
    {
        
    }

    public static function fromRequest(CategoryRequest $request): self
    {

        return new static(
            $request->validated('name'),
            $request->validated('parent_id')
        );

    }
}