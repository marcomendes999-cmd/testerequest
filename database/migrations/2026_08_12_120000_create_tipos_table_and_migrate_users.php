<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $now = now();
        DB::table('tipos')->insert([
            ['name' => 'cliente', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'fornecedor', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'colaborador', 'created_at' => $now, 'updated_at' => $now],
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tipo_id')->nullable()->after('numero')->constrained('tipos')->restrictOnDelete();
        });

        foreach (DB::table('tipos')->pluck('id', 'name') as $name => $id) {
            DB::table('users')->where('tipo', $name)->update(['tipo_id' => $id]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tipo')->nullable()->after('numero');
        });

        DB::table('users')
            ->join('tipos', 'users.tipo_id', '=', 'tipos.id')
            ->update(['users.tipo' => DB::raw('tipos.name')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tipo_id');
        });

        Schema::dropIfExists('tipos');
    }
};
