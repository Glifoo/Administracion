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
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 10, 2)->default(0.00);
            $table->decimal('pago', 10, 2)->default(0.00)->nullable();
            $table->date('fecha')->nullable();
            $table->string('concepto')->nullable();

            $table->foreignId('suscripcion_id')
                ->constrained('suscripcions')
                ->onDelete('cascade');

            $table->foreignId('estadov_id')
                ->nullable()
                ->default(1)
                ->constrained('estatusells');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sells', function (Blueprint $table) {
            $table->dropForeign(['suscripcion_id']);
            $table->dropForeign(['estadov_id']);
        });
        Schema::dropIfExists('sells');
    }
};
