<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
          //  UserSeeder::class,
          //  TecnicoSeeder::class,

            TicketSeeder::class,
            CategorySeeder::class,
            StatusSeeder::class,
            UrgencySeeder::class,
            SubcategorySeeder::class,
            RolesAndPermissionsSeeder::class,
          //  GrupoSeeder::class,
          //  PostoSeeder::class,
          //  UnSeeder::class,
           // TasksTableSeeder::class,
        ]);
    }
}
