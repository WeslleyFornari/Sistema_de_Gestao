<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PagamentosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero' => strtoupper(fake()->lexify('????')) . '-' . fake()->numerify('####'),
            'empresa_id' => 6,
            'user_id'  => null,
            'grupo_id' => 63,
            'categoria_id' => fake()->numberBetween(146, 147), 
            // 'categoria_id' => fake()->randomElement([51, 56]),
            'transacao_key' => 1,
            'id_geteway' => fake()->randomElement([1,2,3]),
            'valor' => fake()->numberBetween(108, 2399 ),
            'id_forma_pagamento' => fake()->randomElement([1,2,3]),
            'bandeira' => fake()->numberBetween(1, 3),
            'taxa'  => fake()->numberBetween(1.3, 4.8 ),
            'valor_liquido' => fake()->numberBetween(105, 2108 ),
            'created_at' => fake()->dateTimeBetween('2025-01-01', '2025-01-31'),
            'status' => 'pago',
        ];
    }
}
