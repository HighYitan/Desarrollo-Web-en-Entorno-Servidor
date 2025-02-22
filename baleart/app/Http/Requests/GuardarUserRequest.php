<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        //return $this->user && $this->user->role->name === 'visitant'; // Permet que l'usuari passi l'autorització si el seu rol és visitant, si no, dona error 403 unauthorized
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom'         => 'nullable|string|max:100|min:2', //Los nombres más cortos son de 2 letras
            'cognom'      => 'nullable|string|max:100|min:2',
            'email'       => 'nullable|string|email|max:100|min:6|unique:users,email,' . $this->user->id . '|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/',
            'telèfon'     => 'nullable|string|max:100|min:7',//Es el número de teléfono más corto del mundo, 7 dígitos
            'contrasenya' => 'nullable|string|max:100|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
        ];
    }
    public function messages()
    {
        return [
            'nom.min'               => "minim 2 car",
            'nom.max'               => "maxim 100 car",
            'nom.string'            => "nom ha de ser una cadena de texte",
            'cognom.min'            => "minim 2 car",
            'cognom.max'            => "maxim 100 car",
            'cognom.string'         => "cognom ha de ser una cadena de texte",
            'email.min'             => "minim 6 car",
            'email.max'             => "maxim 100 car",
            'email.string'          => "email ha de ser una cadena de texte",
            'email.unique'          => "El email ja existeix",
            'email.email'           => "email ha de ser una adreça de correu vàlida",
            'telèfon.min'           => "minim 7 car",
            'telèfon.max'           => "maxim 100 car",
            'telèfon.string'        => "telèfon ha de ser una cadena de texte",
            'contrasenya.min'       => "minim 6 car",
            'contrasenya.max'       => "maxim 100 car",
            'contrasenya.string'    => "contrasenya ha de ser una cadena de texte",
            'contrasenya.regex'     => "contrasenya ha de contenir almenys una minúscula, una majúscula, un número i un caràcter especial",
        ];
    }
}
