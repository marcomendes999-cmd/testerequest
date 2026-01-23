<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Posto; // Note: You'll need to create this model

class UnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtém os IDs dos postos de trabalho para as chaves estrangeiras
        $postoInterno = Posto::where('name', 'Interno')->first()->id;
        $postoExterno = Posto::where('name', 'Externo')->first()->id;
        $postoOnline = Posto::where('name', 'Online')->first()->id;

        DB::table('unidades')->insert([
            // Unidades para o posto de trabalho "Interno" (Grupo: Compras)
            [
                'posto_id' => $postoInterno,
                'name' => 'un1',
                'capacidade' => 8, // 1 turno
                'ordem' => '01',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posto_id' => $postoInterno,
                'name' => 'un2',
                'capacidade' => 16, // 2 turnos
                'ordem' => '02',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Unidades para o posto de trabalho "Externo" (Grupo: Compras)
            [
                'posto_id' => $postoExterno,
                'name' => 'un3',
                'capacidade' => 24, // 3 turnos
                'ordem' => '01',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posto_id' => $postoExterno,
                'name' => 'un4',
                'capacidade' => 8,
                'ordem' => '02',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Unidades para o posto de trabalho "Online" (Grupo: Vendas)
            [
                'posto_id' => $postoOnline,
                'name' => 'un5',
                'capacidade' => 16,
                'ordem' => '01',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
