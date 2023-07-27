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
        Schema::create('ChangeLogs_Comments', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->string('title');
            $table->timestamp('created_at')->useCurrent();
            $table->uuid('author');
            $table->text('content');
            $table->integer('likes')->default(0);
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
