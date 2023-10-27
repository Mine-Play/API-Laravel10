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
        Schema::create('Items', function (Blueprint $table) {
            $table->uuid('id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('legend');
            $table->uuid('category');
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
