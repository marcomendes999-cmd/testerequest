<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Limpar a tabela antes de popular
        DB::table('subcategories')->truncate();

        // Obter as categorias criadas
        $hardwareCategory = Category::where('name', 'Hardware')->first();
        $softwareCategory = Category::where('name', 'Software')->first();
        $networkCategory = Category::where('name', 'Rede')->first();

        // Inserir dados de exemplo para as subcategorias
        // Associar cada subcategoria a uma categoria existente
        $subcategories = [
            ['name' => 'Computador', 'category_id' => $hardwareCategory->id],
            ['name' => 'Impressora', 'category_id' => $hardwareCategory->id],
            ['name' => 'Monitor', 'category_id' => $hardwareCategory->id],
            ['name' => 'Sistema Operativo', 'category_id' => $softwareCategory->id],
            ['name' => 'Aplicação', 'category_id' => $softwareCategory->id],
            ['name' => 'E-mail', 'category_id' => $networkCategory->id],
            ['name' => 'Internet', 'category_id' => $networkCategory->id],
        ];

        foreach ($subcategories as $subcategory) {
            Subcategory::create($subcategory);
        }
    }
}
