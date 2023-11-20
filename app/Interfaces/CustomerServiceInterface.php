<?php

namespace App\Interfaces;

use App\DTO\Customer\CustomerListDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;

interface CustomerServiceInterface {

    public function index(CustomerListDTO $dto): LengthAwarePaginator;

    public function ban(User $customer): User;

    public function delete(User $customer): bool;

}