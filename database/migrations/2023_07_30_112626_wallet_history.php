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
        Schema::create('Wallet_history', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->uuid('wid')->nullable();
            $table->timestamp('date')->useCurrent();
            $table->string('title');
            $table->integer('amount');
            $table->uuid('category')->nullable();
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
