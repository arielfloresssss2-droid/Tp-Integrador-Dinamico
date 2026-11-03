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
        Schema::table('auto', function (Blueprint $table) {
            $table->foreign('id_marca', 'fk_id_marca')->references('id')->on('marcas')->onDelete('cascade');
            $table->foreign('user_id', 'fk_auto_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto', function (Blueprint $table) {
            $table->dropForeign('fk_id_marca');
            $table->dropForeign('fk_auto_user');
        });
    }
};
