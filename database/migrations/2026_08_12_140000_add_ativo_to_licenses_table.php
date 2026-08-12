<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->boolean('ativo')->default(false)->after('expires_at');
        });

        // Mantém a plataforma operacional após a atualização: a licença válida
        // mais recente passa a ser a única licença inicialmente ativa.
        $licenseId = DB::table('licenses')
            ->whereDate('expires_at', '>=', today())
            ->orderByDesc('expires_at')
            ->orderByDesc('id')
            ->value('id');

        if ($licenseId) {
            DB::table('licenses')->where('id', $licenseId)->update(['ativo' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
};
