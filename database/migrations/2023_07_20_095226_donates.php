<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'Site';
    public function up(): void
    {
        Schema::create('Donates', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->json('cost');
            $table->integer('salary')->default(0);
            $table->json("features");
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
