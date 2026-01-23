<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Urgency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UrgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Limpar a tabela antes de popular
        DB::table('urgencies')->truncate();

        // Inserir dados de exemplo para as urgências
        $urgencies = [
            ['name' => 'Baixa'],
            ['name' => 'Média'],
            ['name' => 'Alta'],
            ['name' => 'Crítica'],
        ];

        foreach ($urgencies as $urgency) {
            Urgency::create($urgency);
        }
    }
}
