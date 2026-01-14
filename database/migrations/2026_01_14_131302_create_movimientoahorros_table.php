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
        Schema::create('movimientoahorros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_ahorro_id')
                ->constrained('cuentahorros')
                ->onDelete('cascade');
            $table->enum('tipo', ['deposito', 'retiro']);
            $table->decimal('monto', 12, 2);
            $table->text('concepto')->nullable();
            $table->date('fecha')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientoahorros');
    }
};
