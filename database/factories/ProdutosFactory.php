<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProdutosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      
        return [
            'empresa_id' => 6,
            'grupo_id' => 63,
            'categoria_id' => fake()->numberBetween(146, 147),

            'valor' => fake()->numberBetween(103, 2997 ),
            'status' => 'ativo',
        ];
    }
}
