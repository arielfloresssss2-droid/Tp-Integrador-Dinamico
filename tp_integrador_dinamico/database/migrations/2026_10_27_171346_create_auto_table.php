<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_marca')->index('fk_id_marca');
            $table->string('modelo', 20)->nullable();
            $table->integer('anio')->nullable();
            $table->decimal('precio', 10, 0)->nullable();
            $table->integer('kilometraje')->nullable();
            $table->enum('tipo', ['nuevo', 'usado'])->nullable();
            $table->enum('combustible', ['diesel', 'nafta', 'hibrido', 'electrico'])->nullable();
            $table->enum('estado', ['disponible', 'reservado', 'vendido'])->nullable();
            $table->string('imagen', 50)->nullable();
            $table->string('descripcion', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto');
    }
};
