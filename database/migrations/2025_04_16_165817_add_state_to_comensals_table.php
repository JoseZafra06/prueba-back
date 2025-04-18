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
        Schema::table('comensals', function (Blueprint $table) {
            $table->integer('state')->default(1)->comment('1: Activo 0: Inactivo/Eliminado')->after('direccion');
        });

        Schema::table('mesas', function (Blueprint $table) {
            $table->integer('state')->default(1)->comment('1: Activo 0: Inactivo/Eliminado')->after('ubicacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comensals', function (Blueprint $table) {
            $table->dropColumn('state');
        });

        Schema::table('mesas', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
};
