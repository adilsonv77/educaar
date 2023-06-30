<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->unsignedBigInteger('content_id')->nullable();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->string('glb')->nullable();
            $table->string('marcador')->nullable();
            /*
            >>>Acho que não tem muito sentido fazer esse relacionamento.... isso parece ser mais coerente buscar de conteúdo.
            $table->unsignedBigInteger('disciplina_id');
            $table->foreign('disciplina_id')->references('disciplina_id')->on('turmas_disciplinas')->onDelete('cascade');
            $table->unsignedBigInteger('professor_id')->nullable();
            $table->foreign('professor_id')->references('professor_id')->on('turmas_disciplinas')->onDelete('cascade');
            $table->unsignedBigInteger('turma_id')->nullable();
            $table->foreign('turma_id')->references('turma_id')->on('turmas_disciplinas')->onDelete('cascade');
            */

            // só para registrar quem foi o professor que cadastrou
            $table->unsignedBigInteger('professor_id')->nullable();
            $table->foreign('professor_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
