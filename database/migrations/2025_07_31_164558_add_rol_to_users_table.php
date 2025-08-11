<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('rol_id')
                ->nullable()
                ->default(2)
                ->constrained('rols');
        });
        DB::table('users')->insert([
            [
                'name' => 'Giusti',
                'lastname' => 'Villarroel',
                'phone' => '72501311',
                'email' => 'giusti.17@hotmail.com',
                'password' => Hash::make('17041989'),
                'rol_id' => '1',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
