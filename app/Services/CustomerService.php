<?php

namespace App\Services;

use App\DTO\Customer\CustomerListDTO;
use App\Enums\Roles;
use App\Interfaces\CustomerServiceInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerService implements CustomerServiceInterface {

    public function index(CustomerListDTO $dto): LengthAwarePaginator
    {
        $query = User::role(Roles::CUSTOMER->name());

        if(!is_null($dto->sortBy)){
            $query->orderBy($dto->sortBy, $dto->sortType);
        }

        return $query->paginate($dto->limit ?? 10);
    }

    public function ban(User $customer): User
    {
        $customer->update(['active' => !$customer->active]);

        return $customer;
    }

    public function delete(User $customer): bool
    {

        try{

            DB::transaction(function() use($customer){

                $customer->adverts()->delete();
                $customer->delete();

            });

            return true;

        }catch(\Exception $e){

            throw ValidationException::withMessages(['message' => 'Customer must have customer role!']);

        }

    }

}