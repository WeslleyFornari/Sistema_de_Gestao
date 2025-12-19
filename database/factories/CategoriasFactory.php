<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoriasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empresa_id' => fake()->numberBetween(2, 4),
            'grupo_id' => fake()->numberBetween(46, 54),
            'descricao' => fake()->randomElement([
                'Salgados',
                'Lanches',
                'Bebidas',
                'Pratos Principais',
                'Sobremesas',
                'Acompanhamentos',
                'Entradas',
                'Cafés',
                'Sucos Naturais',
                'Coquetéis',
            ]),
            'status' => 'ativo',
        ];
      
    }
}
