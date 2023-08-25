<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'Global';
    public function up(): void
    {
        Schema::create('Sessions', function (Blueprint $table) {
            $table->uuid('id')->nullable();
            $table->uuid('user_id');
            $table->id('token_id')->unique();
            $table->string('place');
            $table->string('device');
            $table->json('attributes')->nullable();
            $table->timestamp('last_use')->useCurrent();
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
