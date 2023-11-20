<?php

namespace App\Http\Requests\Advert;

use Illuminate\Foundation\Http\FormRequest;

class AdvertListRequest extends FormRequest
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
            'sortBy' => ['nullable', 'required_with:sortType', 'string', 'in:title,price,category,userName,created_at'],
            'sortType' => ['nullable', 'required_with:sortBy', 'string', 'in:asc,desc'],
            'limit' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
            'keyword' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'minPrice' => ['nullable', 'integer'],
            'maxPrice' => ['nullable', 'integer']
        ];
    }
}
