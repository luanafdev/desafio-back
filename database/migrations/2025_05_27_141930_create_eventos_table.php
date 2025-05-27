<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_eventos_table.php
public function up()
{
    Schema::create('eventos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('produtor_id')->constrained();
        $table->string('nome');
        $table->text('descricao');
        $table->string('banner_url');
        $table->string('localizacao');
        $table->string('data_evento');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
