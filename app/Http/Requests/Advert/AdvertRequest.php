<?php

namespace App\Http\Requests\Advert;

use App\Enums\Conditions;
use App\Rules\CategoryHasNoChildren;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\File;

class AdvertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'city_id' => 'city',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.dimensions' => 'Image must be at least 800x600px.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string'],
            'category_id' => ['bail', 'required', 'integer', 'exists:categories,id', new CategoryHasNoChildren],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'condition' => ['required', 'integer', new Enum(Conditions::class)],
            'price' => ['required', 'numeric', 'between:0,9999999999.99'],
            'phone' => ['required', 'string', 'min:9', 'max:15'],
            'image' => ['nullable', Rule::requiredIf(!isset($this->advert)), File::image()->max(3 * 1024)->dimensions(Rule::dimensions()->minWidth(800)->minHeight(600))]
        ];
    }
}
