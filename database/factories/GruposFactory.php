<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GruposFactory extends Factory
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
            'descricao' => fake()->randomElement([
                'Comida Italiana', 
                'Comida Japonesa', 
                'Comida Brasileira', 
                'Comida Chinesa', 
                'Comida Árabe',
                'Comida Mexicana',
                'Comida Espanhola',
                'Comida Portuguesa',
                'Comida Indiana',
                
            ]),
            'status' => 'ativo',
        ];
    }
}
