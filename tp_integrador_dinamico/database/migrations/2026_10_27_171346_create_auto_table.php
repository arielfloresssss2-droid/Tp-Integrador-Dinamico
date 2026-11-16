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
            $table->increments('id');
            $table->unsignedInteger('id_marca')->index('fk_id_marca');
            $table->unsignedBigInteger('user_id')->nullable()->index('fk_auto_user');
            $table->string('modelo', 100);
            $table->integer('anio')->nullable();
            $table->decimal('precio', 12, 2)->default(0);
            $table->integer('kilometraje')->default(0);
            $table->enum('tipo', ['nuevo', 'usado'])->default('usado');
            $table->enum('combustible', ['diesel', 'nafta', 'hibrido', 'electrico'])->default('nafta');
            $table->enum('estado', ['disponible', 'reservado', 'vendido'])->default('disponible');
            $table->string('imagen', 255)->nullable();
            $table->text('descripcion')->nullable();
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
        Schema::dropIfExists('auto');
    }
};
