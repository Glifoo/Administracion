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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->decimal('pago', 10, 2)->default(0.00)->nullable();

            $table->foreignId('ordenpago_id')
                ->constrained('ordenpagos')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['ordenpago_id']);
        });
        Schema::dropIfExists('pagos');
    }
};
