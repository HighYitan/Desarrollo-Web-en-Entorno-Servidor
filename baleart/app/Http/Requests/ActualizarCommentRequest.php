<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarCommentRequest extends FormRequest
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
            'comment' => 'required|min:5|max:5000',
            'score' => 'required|numeric|between:0.00,5.00',
            'status' => 'required|string|in:Y,N',
        ];
    }
    public function messages() {
        return [
            'comment.required' => 'El comentario debe estar informado',
            'comment.min' => 'El comentario mínimo son 5 caracteres',
            'comment.max' => 'El comentario máximo son 5000 caracteres',
            'score.required' => 'La puntuación debe estar informada',
            'score.numeric' => 'La puntuación debe ser un número',
            'score.between' => 'La puntuación debe estar entre 0.00 y 5.00',
            'status.required' => 'El estado de publicación debe estar informado',
            'status.string' => 'El estado de publicación debe ser un texto',
            'status.in' => 'El estado de publicación debe ser Y o N'
        ];
    }
}
