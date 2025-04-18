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
        Schema::create('reservas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->integer('comensal_id')->unsigned()->nullable();
            $table->foreign('comensal_id')->references('id')->on('comensals');
            $table->integer('mesa_id')->unsigned()->nullable();
            $table->foreign('mesa_id')->references('id')->on('mesas');
            $table->date('fecha');
            $table->time('hora');
            $table->integer('numero_de_personas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};