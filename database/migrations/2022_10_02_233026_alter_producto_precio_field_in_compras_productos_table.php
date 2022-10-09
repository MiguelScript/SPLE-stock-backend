<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductoPrecioFieldInComprasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compras_productos', function (Blueprint $table) {
            $table->renameColumn('producto_precio', 'producto_precio_unitario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compras_productos', function (Blueprint $table) {
            $table->renameColumn('producto_precio_unitario', 'producto_precio');
        });
    }
}
