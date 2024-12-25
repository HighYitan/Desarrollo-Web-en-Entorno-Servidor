<?php

namespace App\Http\Requests;

//use App\Models\User;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GuardarCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {   
        $email = $this->input('email'); // Obtenim el valor del camp email
        if (Auth::check()) { // Comprovem si hi ha un usuari autenticat
            return true;
        }
        else if($email) { // Si no hi ha cap usuari autenticat, comprovem si l'email existeix.
            $user = User::where('email', $email);

            if ($user) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required', // El camp email és obligatori per si l'usuari no està autenticat i va per api-key
                'email',
                'string',
                'max:100',
                'min:6',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $value)->whereHas('role', function ($query) {
                        $query->where('name', 'visitant');
                    })->first();

                    if (!$user) {
                        $fail('El ' . $attribute . ' ha de pertànyer a un usuari amb el rol de visitant');
                    }
                },
            ],
            'comentaris' => 'required|array', // Valida que el camp comentaris sigui un array
            'comentaris.*.comentari' => 'required|string|min:3|max:5000', 
            'comentaris.*.puntuació' => [
                'required',
                'regex:/^\d(\.\d{1,2})?$/', // Valida un número decimal amb 1 dígit abans del punt decimal i fins a 2 dígits després
                "numeric",
                "between:0.00,5.00",
            ],
            'comentaris.*.imatges' => 'nullable|array', // Valida que el camp imatges sigui un array
            'comentaris.*.imatges.*.imatge_url' => 'nullable|string|url|max:100',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'L\'email de l\'usuari és obligatori',
            'email.email' => 'L\'email ha de ser una adreça de correu electrònic vàlida',
            'email.string' => 'L\'email ha de ser una cadena de text',
            'email.min' => 'L\'email ha de tenir almenys 6 caràcters',
            'email.max' => 'L\'email no pot tenir més de 100 caràcters',
            'email.regex' => 'L\'email ha de ser una adreça de correu electrònic vàlida',
            'comentaris.required' => 'Els comentaris són obligatoris',
            'comentaris.array' => 'Els comentaris han de ser un array',
            'comentaris.*.comentari.required' => 'El comentari és obligatori',
            'comentaris.*.comentari.string' => 'El comentari ha de ser una cadena de text',
            'comentaris.*.comentari.min' => 'El comentari ha de tenir almenys 3 caràcters',
            'comentaris.*.comentari.max' => 'El comentari no pot tenir més de 5000 caràcters',
            'comentaris.*.puntuació.required' => 'La puntuació és obligatòria',
            'comentaris.*.puntuació.regex' => 'La puntuació ha de ser un número decimal amb fins a 2 dígits després del punt',
            'comentaris.*.puntuació.numeric' => 'La puntuació ha de ser un número',
            'comentaris.*.puntuació.between' => 'La puntuació ha de ser un número entre 0 i 5',
            'comentaris.*.imatges.array' => 'Les imatges han de ser un array',
            'comentaris.*.imatges.*.imatge_url.string' => 'La URL de la imatge ha de ser una cadena de text',
            'comentaris.*.imatges.*.imatge_url.url' => 'La URL de la imatge ha de ser una URL vàlida',
            'comentaris.*.imatges.*.imatge_url.max' => 'La URL de la imatge no pot tenir més de 100 caràcters',
        ];
    }
}
