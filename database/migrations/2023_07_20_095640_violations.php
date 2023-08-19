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
        Schema::create('Violations', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->uuid('user');
            $table->string('type');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('ending_at')->nullable();
            $table->string('moderator')->nullable();
            $table->string('rules');
            $table->string('message')->nullable();
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
    