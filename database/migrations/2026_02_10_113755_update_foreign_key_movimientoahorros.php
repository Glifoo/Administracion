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
        Schema::table('movimientoahorros', function (Blueprint $table) {
            $table->dropForeign(['cuenta_ahorro_id']);
            $table->foreign('cuenta_ahorro_id')
                ->references('id')
                ->on('cuentahorros')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientoahorros', function (Blueprint $table) {
            $table->dropForeign(['cuenta_ahorro_id']);
            $table->foreign('cuenta_ahorro_id')
                ->references('id')
                ->on('cuentahorros')
                ->onDelete('cascade');
        });
    }
};
