<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
            'nom' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'marque' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'annee' => 'sometimes|required|digits:4|integer|min:1900|max:' . date('Y'),
            'prix' => 'sometimes|required|numeric|min:0',
            'kilometrage' => 'nullable|string|max:255',
            'carburant' => 'sometimes|required|string|max:50',
            'transmission' => 'sometimes|required|string|max:50',
            'couleur' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:nouveau,occasion',
            'images'       => 'nullable|array',
            'images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }
}
