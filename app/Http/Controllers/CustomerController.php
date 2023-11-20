<?php

namespace App\Http\Controllers;

use App\DTO\Customer\CustomerListDTO;
use App\Enums\Roles;
use App\Http\Requests\Customer\CustomerListRequest;
use App\Interfaces\CustomerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function __construct(private CustomerServiceInterface $customerService)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(CustomerListRequest $request)
    {
        $data = $this->customerService->index(CustomerListDTO::fromRequest($request));

        return View::make('customer.index', compact('data'));
    }

    public function ban(User $customer): JsonResponse
    {
        if($customer->hasRole(Roles::ADMIN->name())){

            throw ValidationException::withMessages(['message' => 'Customer must have customer role!']);

        }

        $this->customerService->ban($customer);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $customer): JsonResponse
    {
        if($customer->hasRole(Roles::ADMIN->name())){

            throw ValidationException::withMessages(['message' => 'Customer must have customer role!']);

        }
        
        $this->customerService->delete($customer);

        return response()->json(['success' => true]);
    }
}
