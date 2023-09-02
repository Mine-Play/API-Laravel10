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
        Schema::create('Vip_referals', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('author_id');
            $table->string('referal')->unique();
            $table->integer('percents');
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
