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
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();

            $table->text('nombre');
            $table->text('detalle')->nullable();
            $table->decimal('costo', 10, 2)->default(0.00);
            $table->decimal('cantidad', 10, 2)->default(0.00)->nullable();

            $table->foreignId('trabajo_id')
                ->constrained('trabajos')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->dropForeign(['trabajo_id']);
            $table->dropForeign(['medida_id']);

        });
        Schema::dropIfExists('insumos');
    }
};
