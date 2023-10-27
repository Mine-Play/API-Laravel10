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
        // Schema::create('Shop_items', function (Blueprint $table) {
        //     $table->uuid('id')->nullable();
        //     $table->string('title');
        //     $table->text('description');
        //     $table->integer('vault');
        //     $table->integer('cost');
        //     $table->uuid('category');
        //     $table->string('type');
        //     $table->integer('purchases')->default(0);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
