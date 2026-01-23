<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Ticket;
use Faker\Factory as Faker;


class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_PT');      

        // Adicionar mais tickets com Faker
        for ($i = 0; $i < 2; $i++) {
            Ticket::create([
                'num_operario' => $faker->name,
                'idcategoria' => $faker->numberBetween(1, 6),
                'grupo_id' => 1,//$faker->numberBetween(1, 3),
                'idurgencia' => $faker->numberBetween(1, 3),
                'prazo' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                'titulo' => $faker->sentence(3),
                'descricao' => $faker->paragraph,
                'code' => 'SI2025' . str_pad($i + 28, 4, '0', STR_PAD_LEFT),
                'userid' => sprintf('%04d', $faker->numberBetween(1, 6)),
                'email' => $faker->safeEmail,
                'datafecho' => $faker->optional(0.3)->dateTimeThisMonth,
                'prazoini' => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
                'prazotmp' => null,
                'created_at' => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
                'resolvido' => $faker->numberBetween(1, 2),
                'aprovado' => $faker->numberBetween(1, 2),
                'idestado' => $faker->numberBetween(1, 4),
                'area' => $faker->randomElement(['TI', 'RH', 'Armazém', 'Escritório', 'Remoto']),
                'idsubcategoria' => $faker->numberBetween(1, 6),
                'pcnumber' => $faker->optional(0.5)->regexify('PC[0-9]{3}'),
                'peso' => 1,
            ]);
            
        }
    }
}
