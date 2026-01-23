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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // FKs
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // un_id (posto de trabalho) — não sabemos a tabela, deixamos como unsignedBigInteger nullable
            $table->unsignedBigInteger('un_id')->nullable()->index();

            // Conteúdo da task
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->integer('ordem')->default(1);

            // estado da task, referencia tabela statuses (ou outra que uses)
            $table->foreignId('estado_id')->nullable()->constrained('statuses')->onDelete('set null');

            // datas / tempos
            $table->date('prazo')->nullable();
            $table->dateTime('data_ini')->nullable(); // início previsto/real
            $table->time('time')->nullable(); // duração ou hora (usar conforme necessidade)

            // flags e ligações
            $table->boolean('disponivel')->default(true);
            $table->unsignedBigInteger('dependencia')->nullable()->index(); // pode apontar para outra task.id

            $table->timestamps();

            // Se quiseres, podes adicionar constraint self-referencing (opcional)
            // Note: muitos DBMS não permitem criar FK self-ref depois do create; se quiseres FK,
            // cria-a numa migration posterior ou usa raw SQL. Aqui só criámos índices para dependência.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
