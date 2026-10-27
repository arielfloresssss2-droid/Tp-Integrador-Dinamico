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
        Schema::create('ventas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_cliente')->index('fk_id_cliente');
            $table->integer('id_auto')->index('fk_id_auto');
            $table->integer('id_vendedor')->index('fk_id_vendedor');
            $table->date('fecha_venta')->nullable();
            $table->decimal('precio_final', 10, 0)->nullable();
            $table->enum('metodo_pago', ['efectivo', 'transferencia'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
};
