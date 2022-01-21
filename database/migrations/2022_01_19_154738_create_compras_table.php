<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('proveedor_id')->unsigned()->nullable();

            $table->string('num_factura');
            $table->string('fecha_compra');
            $table->string('responsable_creacion')->nullable();
            $table->string('responsable_edicion')->nullable();
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('proveedor_id')
                  ->references('id')
                  ->on('proveedores')
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
        Schema::dropIfExists('compras');
    }
}
