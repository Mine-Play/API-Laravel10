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
        Schema::create('News_comments', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->uuid('new_id');
            $table->uuid('author');
            $table->string('is_reply')->default(null);
            $table->timestamp('created_at')->useCurrent();
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
