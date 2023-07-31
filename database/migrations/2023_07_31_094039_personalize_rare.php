<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'Minigames';
    public function up(): void
    {
        Schema::create('Personalize_rare', function (Blueprint $table) {
            $table->uuid('id')->nullable();
            $table->string('title');
            $table->string('gradient');
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
