<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Limpar a tabela antes de popular
        DB::table('statuses')->truncate();

        // Inserir dados de exemplo para os estados
        $statuses = [
            ['name' => 'Aberto'],
            ['name' => 'Em Progresso'],
            ['name' => 'Fechado'],
            ['name' => 'Resolvido'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
