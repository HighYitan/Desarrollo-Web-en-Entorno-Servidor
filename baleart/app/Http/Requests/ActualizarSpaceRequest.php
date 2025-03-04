<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarSpaceRequest extends FormRequest
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
            //'title' => ['required','unique:posts','min:5','max:255', new Uppercase],
            'name' => 'required|min:5|max:100',
            'regNumber' => 'required|min:5|max:10',
            'observation_CA' => 'required|min:5|max:5000',
            'observation_ES' => 'required|min:5|max:5000',
            'observation_EN' => 'required|min:5|max:5000',
            'email'       => 'required|string|email|max:100|min:6|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/',
            'phone' => 'required|string|max:100|min:7',
            'website' => 'required|string|min:7|max:100',
            'accessType' => 'required|string|min:1|max:1|regex:/^[nps]$/',
            'modality_id' => 'required|array',
            'modality_id.*' => 'exists:modalities,id',
            'service_id' => 'required|array',
            'service_id.*' => 'exists:services,id',
        ];
    }
    public function messages() {
        return [
            'name.required' => 'El espacio debe estar informado',
            'name.min' => 'Nombre mínimo son 5 carateres',
            'name.max' => 'Nombre máximo son 100 caracters',
            'regNumber.required' => 'El número de registro debe estar informado',
            'regNumber.min' => 'El número de registro mínimo son 5 carateres',
            'regNumber.max' => 'El número de registro máximo son 10 caracters',
            'observation_CA.required' => 'La descripció debe estar informada',
            'observation_CA.min' => 'La descripció mínima son 5 carateres',
            'observation_CA.max' => 'La descripció máxima son 5000 caracters',
            'observation_ES.required' => 'La descripció debe estar informada',
            'observation_ES.min' => 'La descripció mínima son 5 carateres',
            'observation_ES.max' => 'La descripció máxima son 5000 caracters',
            'observation_EN.required' => 'La descripció debe estar informada',
            'observation_EN.min' => 'La descripció mínimo son 5 carateres',
            'observation_EN.max' => 'La descripció máximo son 5000 caracters',
            'email.required' => 'El email debe estar informado',
            'email.string' => 'El email debe ser una cadena de texto',
            'email.email' => 'El email debe ser una dirección de correo válida',
            'email.min' => 'El email mínimo son 6 caracteres',
            'email.max' => 'El email máximo son 100 caracteres',
            'email.regex' => 'El email debe ser una dirección de correo válida',
            'phone.required' => 'El teléfono debe estar informado',
            'phone.string' => 'El teléfono debe ser una cadena de texto',
            'phone.min' => 'El teléfono mínimo son 7 caracteres',
            'phone.max' => 'El teléfono máximo son 100 caracteres',
            'website.required' => 'La web debe estar informada',
            'website.string' => 'La web debe ser una cadena de texto',
            'website.min' => 'La web mínimo son 7 caracteres',
            'website.max' => 'La web máximo son 100 caracteres',
            'accessType.required' => 'El tipo de acceso debe estar informado',
            'accessType.string' => 'El tipo de acceso debe ser una cadena de texto',
            'accessType.min' => 'El tipo de acceso mínimo es 1 carácter',
            'accessType.max' => 'El tipo de acceso máximo es 1 caracteres',
            'accessType.regex' => 'El tipo de acceso debe ser "n", "p" o "s"',
            'modality_id.required' => 'Las modalidades deben estar informadas',
            'modality_id.array' => 'Las modalidades deben ser un array',
            'modality_id.*.exists' => 'Cada modalidad debe existir en la base de datos',
            'service_id.required' => 'Los servicios deben estar informados',
            'service_id.array' => 'Los servicios deben ser un array',
            'service_id.*.exists' => 'Cada servicio debe existir en la base de datos',
        ];
    }
}
