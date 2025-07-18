<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ];
    }
}
