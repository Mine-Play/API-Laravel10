<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'Textures';
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->integer('skin')->default(0);
            $table->integer('cloak')->default(0);
            $table->integer('avatar')->default(0);
            $table->integer('banner')->default(0);
            $table->json('params')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
