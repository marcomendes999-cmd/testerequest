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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('num_operario', 255)->nullable();
            $table->integer('idcategoria');
            $table->integer('grupo_id');
            $table->integer('idurgencia');
            $table->date('prazo')->nullable();
            $table->string('titulo', 255)->nullable();
            $table->text('descricao')->nullable();
            $table->string('code', 100)->nullable();
            $table->string('userid', 4)->nullable(); // Técnico (ligado a users ou tecnicos)
            $table->string('email', 255)->nullable();
            $table->dateTime('datafecho')->nullable();
            $table->dateTime('prazoini')->nullable();
            $table->dateTime('prazotmp')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('resolvido')->default(2);
            $table->integer('aprovado')->default(2);
            $table->integer('idestado');
            $table->string('area', 255)->nullable();
            $table->integer('idsubcategoria');
            $table->string('pcnumber', 15)->nullable();
            $table->integer('peso')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
