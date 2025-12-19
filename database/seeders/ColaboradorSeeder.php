<?php

namespace Database\Seeders;

use App\Models\Bandeira;
use App\Models\Colaborador;
use App\Models\GrupoEconomico;
use App\Models\Unidade;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ColaboradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grupo = GrupoEconomico::firstOrCreate(['nome' => 'Grupo Padrão']);
        
        $bandeira = Bandeira::firstOrCreate(
            ['nome' => 'Bandeira Padrão', 'grupo_economico_id' => $grupo->id]
        );

        $unidade = Unidade::firstOrCreate(
            [
                'nome_fantasia' => 'Voch Tech',
                'razao_social' => 'Voch Tech LTDA',
                'cnpj' => '00.000.000/0001-00',
                'bandeira_id' => $bandeira->id,
            ]
        );

        $colaborador = Colaborador::firstOrCreate(
            [
                'nome' => 'Administrador',
                'email' => 'admin@admin',
                'cpf' => '000.000.000-00', 
                'unidade_id' => $unidade->id,
            ]
        );

        User::firstOrCreate(
            ['email' => $colaborador-> email],
            [
                'colaborador_id' => $colaborador->id,
                'password' => Hash::make('password'), 
                'role' => 'admin'
            ]
        );

        // foreach (range(1, 50) as $index) {

        //    $colaborador =  Colaborador::create([
        //         'nome' => "Colaborador " . $index,
        //         'email' => "colaborador" . $index . "@email.com",
        //         'cpf' => "111.222.333-" . str_pad($index, 2, '0', STR_PAD_LEFT),
        //         'unidade_id' => rand(2, 5),
        //     ]);

        //     User::create([
                   
        //             'email' => $colaborador->email,
        //             'password' => Hash::make('password'), 
        //             'role' => 'user', 
        //             'colaborador_id' => $colaborador->id,
        //         ]);
        // }
    
    }
}
