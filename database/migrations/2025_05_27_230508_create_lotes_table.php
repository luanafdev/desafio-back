<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('lotes', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key (bigint)
            $table->foreignId('setor_id')->constrained(); // Foreign key to 'setores' table
            $table->string('nome'); // Name of the batch/lot

            // Changed from 'number' to 'decimal' for precise currency storage
            $table->decimal('preco', 10, 2); // Price, e.g., 99999999.99

            $table->integer('quantidade'); // Quantity of items in the batch/lot
            $table->dateTime('data_inicio'); // Start date and time of the batch/lot
            $table->dateTime('data_fim');   // End date and time of the batch/lot
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
