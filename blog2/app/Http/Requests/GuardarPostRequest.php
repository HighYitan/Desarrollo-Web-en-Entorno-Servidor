<?php

namespace App\Http\Requests;

use App\Rules\Uppercase;
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
            'title' => ['required','unique:posts','min:5','max:255', new Uppercase],
            'url_clean' => 'required|unique:posts|min:5|max:255',
            'content' => 'required|min:5|max:255',
        ];
    }
    /*public function messages()
    {
        return [
            'title.required' => "Has d'informar un títol",
            'title.unique' => " El títol ja existeix",
            'title.min' => "minim 5 car",
            'title.max' => "maxim 255 car",
            'url_clean.required' => "Has d'informar d'una url"
        ];
    }*/
    public function messages() {
        return [
            'title.required' => 'El título debe estar informado',
            'title.unique' => 'El título ya existe, no se puede duplicar',
            'title.min' => 'Título mínimo son 5 carateres',
            'title.max' => 'Título máximo son 255 caracters',
            'url_clean.required' => 'La url debe estar informada',
            'url_clean.unique' => 'La url ya existe, no se puede duplicar',
            'url_clean.min' => 'La url mínimo son 5 carateres',
            'url_clean.max' => 'La url máximo son 255 caracters',
            'content.required' => 'El content debe estar informada',
            'content.min' => 'El content mínimo son 5 carateres',
            'content.max' => 'El content máximo son 255 caracters',
        ]; 
    }
}
