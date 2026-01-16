<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cuenta_trabajos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cuenta_id')
                ->constrained('cuentahorros')
                ->cascadeOnDelete();

            $table->foreignId('trabajo_id')
                ->constrained('trabajos')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['cuenta_id', 'trabajo_id']);
            $table->index('cuenta_id');
            $table->index('trabajo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_trabajos');
    }
};
