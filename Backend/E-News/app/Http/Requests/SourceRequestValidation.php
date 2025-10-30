<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SourceRequestValidation extends FormRequest
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
            'name' => 'required | string | max:255',
            'slug' => 'required | string | max:255',
            'url_logo' => 'required | string | url',
            'is_active' => 'required | boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire',
            'slug.required' => 'Le slug est obligatoire',
            'url_logo.required' => 'L\'url de l\'image est obligatoire',
            'is_active.required' => 'L\'actif est obligatoire',
        ];
    }
}
