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
        Schema::create('violations', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->uuid('user')->unique();
            $table->string('type');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('ending_at');
            $table->uuid('moderator');
            $table->json('rules');
            $table->string('message')->default(null);
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
