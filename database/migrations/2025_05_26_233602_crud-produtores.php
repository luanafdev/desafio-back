<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('produtores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuario_id')->constrained('users');
        $table->string('nome_empresa');
        $table->timestamps();
    });
}

};
