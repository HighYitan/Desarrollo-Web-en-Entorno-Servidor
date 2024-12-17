<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;  // Permet que l'usuari passi l'autorització
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|unique:posts|min:5|max:255',
            'url_clean' => 'required|unique:posts|min:5|max:255',
            'content' => 'required|min:5|max:255',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => "Has d'informar un títol",
            'title.unique' => " El títol ja existeix",
            'title.min' => "minim 5 car",
            'title.max' => "maxim 255 car",
            'url_clean.required' => "Has d'informar d'una url"
        ];
    }
}
