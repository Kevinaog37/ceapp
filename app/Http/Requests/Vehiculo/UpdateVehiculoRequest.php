<?php

namespace App\Http\Requests\Vehiculo;

use App\Models\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehiculo = $this->route('vehiculo');

        return [
            'placa' => ['sometimes', 'required', 'string', 'max:10', Rule::unique('vehiculos', 'placa')->ignore($vehiculo?->id)],
            'tipo' => ['sometimes', 'required', Rule::in([Vehiculo::TIPO_MOTO, Vehiculo::TIPO_CARRO])],
            'marca' => ['sometimes', 'required', 'string', 'max:255'],
            'modelo' => ['sometimes', 'required', 'string', 'max:255'],
            'anio' => ['sometimes', 'required', 'integer', 'min:1980', 'max:'.(date('Y') + 1)],
            'km_actual' => ['sometimes', 'required', 'integer', 'min:0'],
            'estado' => ['sometimes', 'required', Rule::in([
                Vehiculo::ESTADO_ACTIVO,
                Vehiculo::ESTADO_EN_MANTENIMIENTO,
                Vehiculo::ESTADO_INACTIVO,
            ])],
            'fecha_vinculacion' => ['sometimes', 'required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'placa.unique' => 'Ya existe un vehículo registrado con esta placa.',
        ];
    }
}
