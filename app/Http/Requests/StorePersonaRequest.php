<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'           => ['required', 'string', 'max:100'],
            'paterno'          => ['required', 'string', 'max:100'],
            'materno'          => ['required', 'string', 'max:100'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'           => 'El nombre es obligatorio.',
            'nombre.max'                => 'El nombre no puede superar 100 caracteres.',
            'paterno.required'          => 'El apellido paterno es obligatorio.',
            'paterno.max'               => 'El apellido paterno no puede superar 100 caracteres.',
            'materno.required'          => 'El apellido materno es obligatorio.',
            'materno.max'               => 'El apellido materno no puede superar 100 caracteres.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date'     => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_nacimiento.before'   => 'La fecha de nacimiento debe ser anterior a hoy.',
        ];
    }
}
