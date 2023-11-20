<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sortBy' => ['nullable', 'required_with:sortType', 'string', 'in:name,email'],
            'sortType' => ['nullable', 'required_with:sortBy', 'string', 'in:asc,desc'],
            'limit' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer']
        ];
    }
}
