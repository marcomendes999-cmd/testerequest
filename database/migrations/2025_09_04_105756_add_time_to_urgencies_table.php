<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urgencies', function (Blueprint $table) {
            $table->integer('time')->nullable()->after('ordem'); // adiciona após 'ordem'
        });
    }

    public function down(): void
    {
        Schema::table('urgencies', function (Blueprint $table) {
            $table->dropColumn('time');
        });
    }
};
