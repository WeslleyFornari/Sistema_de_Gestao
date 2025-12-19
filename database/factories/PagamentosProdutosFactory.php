<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PagamentosProdutosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'id_pagamento' =>  fake()->numberBetween(395, 445),
            'id_produto' => fake()->numberBetween(262, 279),
            'qtd' => 1,
            'valor'  => fake()->numberBetween(108, 2099),
        ];
    }
}
