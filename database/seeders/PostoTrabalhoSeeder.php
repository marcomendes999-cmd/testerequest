<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;

class PostoTrabalhoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pega os IDs dos grupos para as chaves estrangeiras
        $grupoCompras = Grupo::where('name', 'Compras')->first()->id;
        $grupoVendas = Grupo::where('name', 'Vendas')->first()->id;

        DB::table('postos')->insert([
            // Postos de Trabalho para o grupo "Compras"
            [
                'grupo_id' => $grupoCompras,
                'name' => 'Interno',
                'ordem' => '01',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grupo_id' => $grupoCompras,
                'name' => 'Externo',
                'ordem' => '02',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Posto de Trabalho para o grupo "Vendas"
            [
                'grupo_id' => $grupoVendas,
                'name' => 'Online',
                'ordem' => '01',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
