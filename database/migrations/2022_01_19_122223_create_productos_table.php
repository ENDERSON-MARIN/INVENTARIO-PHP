<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('categoria_id')->unsigned()->nullable();
            $table->string('nombre');
            $table->string('referencia');
            $table->decimal('peso', 11, 2);
            $table->string('fecha_elaboracion');
            $table->decimal('stock', 11, 2);
            $table->string('estado');
            $table->text('observaciones')->nullable();
            $table->string('responsable_creacion')->nullable();
            $table->string('responsable_edicion')->nullable();


            $table->softDeletes();
            $table->timestamps();

            $table->foreign('categoria_id')
            ->references('id')
            ->on('categorias')
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
        Schema::dropIfExists('productos');
    }
}
