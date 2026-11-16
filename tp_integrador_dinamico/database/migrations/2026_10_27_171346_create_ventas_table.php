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
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('fk_ventas_user');
            $table->unsignedInteger('id_cliente')->nullable()->index('fk_id_cliente');
            $table->unsignedInteger('id_auto')->index('fk_id_auto');
            $table->unsignedInteger('id_vendedor')->nullable()->index('fk_id_vendedor');
            $table->date('fecha_venta')->nullable();
            $table->decimal('precio_final', 12, 2)->nullable();
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta'])->default('transferencia');
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
        Schema::dropIfExists('ventas');
    }
};
