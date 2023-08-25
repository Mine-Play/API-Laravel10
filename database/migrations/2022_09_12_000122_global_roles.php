<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'Global';
    public function up()
    {
        Schema::create('Roles', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->string('title');
            $table->string('color');
            $table->integer('index');
            $table->integer('default')->default(0);
            $table->json('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};