<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
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

            $table->integer('cliente_id')->unsigned()->nullable();

            $table->string('num_factura');
            $table->string('fecha_venta');
            $table->string('responsable_creacion')->nullable();
            $table->string('responsable_edicion')->nullable();
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('cliente_id')
                  ->references('id')
                  ->on('clientes')
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
        Schema::dropIfExists('ventas');
    }
}
