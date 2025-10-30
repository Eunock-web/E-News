<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticlesRequestValidation extends FormRequest
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
            'title' => 'required | string | max:255',
            'category' => 'required | string | max:255',
            'summary' => 'required | string | max:255',
            'content' => 'required | text',
            'image_url' => 'required | string | url',
            'published_at' => 'required | string | date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire',
            'category.required' => 'La catégorie est obligatoire',
            'summary.required' => 'Le résumé est obligatoire',
            'content.required' => 'Le contenu est obligatoire',
            'image_url.required' => 'L\'image est obligatoire',
            'published_at.required' => 'La date de publication est obligatoire',
        ];
    }
}
