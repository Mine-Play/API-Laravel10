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
        Schema::create('PlayPass_users', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->uuid('user')->unique();
            $table->integer('level')->default(1);
            $table->integer('tokens')->default(0);
            $table->string('type')->default('standart');
            $table->string('not_equiped_standart')->nullable();
            $table->string('not_equiped_gold')->nullable();
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
