<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moradas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_externo');
            $table->string('tipo'); // Rua, Avenida, Travessa
            $table->string('rua');  // nome da via
            $table->string('numero')->nullable();
            $table->string('cidade');
            $table->string('codigo_postal', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moradas');
    }
};
