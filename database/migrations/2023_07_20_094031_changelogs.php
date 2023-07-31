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
        Schema::create('ChangeLogs', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->string('title');
            $table->timestamp('created_at')->useCurrent();
            $table->uuid('author');
            $table->text('description');
            $table->text('content');
            $table->string('comment');
            $table->json('attrs')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('comments')->default(0);
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
