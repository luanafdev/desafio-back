<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('produtores', function (Blueprint $table) {
        // Drop foreign key
        $table->dropForeign(['usuario_id']);
        
        // Alter column type
        $table->dropColumn('usuario_id');
        $table->unsignedBigInteger('usuario_id');

        // Recreate foreign key
        $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('produtores', function (Blueprint $table) {
        $table->dropForeign(['usuario_id']);
        $table->dropColumn('usuario_id');
        $table->uuid('usuario_id');

        $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
    });
}

};
