<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('identificacion');
            $table->string('nombres');
            $table->string('estado');
            $table->string('correo')->nullable();
            $table->string('telefonos')->nullable();
            $table->text('direccion')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('responsable_creacion')->nullable();
            $table->string('responsable_edicion')->nullable();
            $table->string('imagen')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('clientes');
    }
}
