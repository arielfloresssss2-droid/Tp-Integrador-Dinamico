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
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_id_cliente')->references(['id'])->on('cliente');
            $table->foreign(['id_auto'], 'fk_id_auto')->references(['id'])->on('auto');
            $table->foreign(['id_vendedor'], 'fk_id_vendedor')->references(['id'])->on('vendedor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign('fk_id_cliente');
            $table->dropForeign('fk_id_auto');
            $table->dropForeign('fk_id_vendedor');
        });
    }
};
