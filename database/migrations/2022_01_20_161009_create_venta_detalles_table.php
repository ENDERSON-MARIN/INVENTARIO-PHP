<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('venta_id')->unsigned()->nullable();
            $table->integer('producto_id')->unsigned()->nullable();
            $table->decimal('cantidad', 11, 2);
            $table->decimal('precio_venta', 11, 2);
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('venta_id')
                  ->references('id')
                  ->on('ventas')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->foreign('producto_id')
                  ->references('id')
                  ->on('productos')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venta_detalles');
    }
}
