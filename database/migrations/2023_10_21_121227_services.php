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
        Schema::create('Services', function (Blueprint $table) {
            $table->uuid('id')->nullable()->index();
            $table->string('title');
            $table->text('description');
            $table->uuid('cost');
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
