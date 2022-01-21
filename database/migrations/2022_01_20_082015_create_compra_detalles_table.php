<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompraDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_detalles', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('compra_id')->unsigned()->nullable();
            $table->integer('producto_id')->unsigned()->nullable();
            $table->decimal('cantidad', 11, 2);
            $table->decimal('precio_compra', 11, 2);
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('compra_id')
                  ->references('id')
                  ->on('compras')
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
        Schema::dropIfExists('compra_detalles');
    }
}
