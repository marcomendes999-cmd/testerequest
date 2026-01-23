<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Limpar a tabela antes de popular
        DB::table('categories')->truncate();

        // Inserir dados de exemplo para as categorias
        $categories = [
            ['name' => 'Hardware'],
            ['name' => 'Software'],
            ['name' => 'Rede'],
            ['name' => 'Outros'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
