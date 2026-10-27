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
        Schema::create('cliente', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 20)->nullable();
            $table->string('apellido', 20)->nullable();
            $table->string('dni', 20)->nullable();
            $table->integer('telefono')->nullable();
            $table->string('direccion', 20)->nullable();
            $table->string('email', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente');
    }
};
