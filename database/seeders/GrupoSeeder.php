<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grupos')->insert([
            [
                'name' => 'Compras',
                'ordem' => '01',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vendas',
                'ordem' => '02',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Produção',
                'ordem' => '03',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
