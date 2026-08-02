<?php

namespace App\Http\Requests\Vehiculo;

use App\Models\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La autorización real (por rol/policy) se hace en el controlador con $this->authorize().
        // Aquí solo se exige estar autenticado, lo cual ya garantiza el middleware de la ruta.
        return true;
    }

    public function rules(): array
    {
        return [
            'placa' => ['required', 'string', 'max:10', 'unique:vehiculos,placa'],
            'tipo' => ['required', Rule::in([Vehiculo::TIPO_MOTO, Vehiculo::TIPO_CARRO])],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'anio' => ['required', 'integer', 'min:1980', 'max:'.(date('Y') + 1)],
            'km_actual' => ['nullable', 'integer', 'min:0'],
            'estado' => ['nullable', Rule::in([
                Vehiculo::ESTADO_ACTIVO,
                Vehiculo::ESTADO_EN_MANTENIMIENTO,
                Vehiculo::ESTADO_INACTIVO,
            ])],
            'fecha_vinculacion' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'placa.unique' => 'Ya existe un vehículo registrado con esta placa.',
            'anio.max' => 'El año del vehículo no puede ser mayor a '.(date('Y') + 1).'.',
        ];
    }
}
