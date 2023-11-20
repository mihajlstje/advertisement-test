<?php

namespace App\DTO\Customer;

use App\Http\Requests\Customer\CustomerListRequest;

class CustomerListDTO {

    public function __construct(
        public readonly ?string $sortBy,
        public readonly ?string $sortType,
        public readonly ?int $limit,
        public readonly ?int $page
    )
    {
        
    }

    public static function fromRequest(CustomerListRequest $request){

        return new static(
            $request->validated('sortBy'),
            $request->validated('sortType'),
            $request->validated('limit'),
            $request->validated('page'),
        );

    }
}