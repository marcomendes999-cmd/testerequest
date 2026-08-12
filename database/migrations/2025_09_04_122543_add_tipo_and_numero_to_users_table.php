<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('numero', 4)->nullable()->after('password'); // varchar(4) para número
            $table->enum('tipo', ['cliente', 'fornecedor', 'colaborador'])->default('cliente')->after('numero'); // select box
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('numero');
            $table->dropColumn('tipo');
        });
    }
};
