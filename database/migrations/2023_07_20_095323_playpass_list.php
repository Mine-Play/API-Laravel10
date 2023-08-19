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
        Schema::create('PlayPass_list', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->string('name');
            $table->integer('season')->unique();
            $table->timestamp('beginning_at')->useCurrent();
            $table->timestamp('ending_at')->nullable();
            $table->json('things_standart');
            $table->json('things_gold');
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
