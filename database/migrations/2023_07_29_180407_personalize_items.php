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
        Schema::create('Personalize_items', function (Blueprint $table) {
            $table->uuid('id')->nullable();
            $table->uuid('category');
            $table->uuid('position');
            $table->string('name');
            $table->string('rare');
            $table->text('description');
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
