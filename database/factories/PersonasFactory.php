<?php

namespace Database\Factories;

use App\Models\Personas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personas>
 */
class PersonasFactory extends Factory
{
    protected $model = Personas::class;

    public function definition(): array
    {
        return [
            'nombre'           => fake()->firstName(),
            'paterno'          => fake()->lastName(),
            'materno'          => fake()->lastName(),
            'fecha_nacimiento' => fake()->dateTimeBetween('-60 years', '-10 years')->format('Y-m-d'),
        ];
    }
}
