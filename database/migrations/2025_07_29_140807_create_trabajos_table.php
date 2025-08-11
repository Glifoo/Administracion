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
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();

            $table->string('trabajo');
            $table->text('descripcion');
            $table->decimal('cantidad', 10, 2);

            $table->enum('estado', ['por cotizar', 'cotizado', 'rechazada'])->default('por cotizar');

            $table->decimal('manobra')->nullable();

            $table->decimal('ganancia', 10, 2)->default(0.00)->nullable();
            $table->decimal('gananciaefectivo', 10, 2)->default(0.00)->nullable();

            $table->decimal('iva', 10, 2)->default(0.00)->nullable();
            $table->decimal('ivaefectivo', 10, 2)->default(0.00)->nullable();

            $table->decimal('Costofactura', 10, 2)->default(0.00)->nullable();

            $table->decimal('Costoproduccion', 10, 2)->default(0.00)->nullable();
            $table->decimal('Costofinal', 10, 2)->default(0.00)->nullable();

            $table->foreignId('cliente_id')
                ->constrained('clients')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajos', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
        });
        Schema::dropIfExists('trabajos');
    }
};
