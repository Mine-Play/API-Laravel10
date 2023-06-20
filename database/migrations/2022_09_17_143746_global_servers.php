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
        Schema::create('Servers', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->string('name');
            $table->string('slug');
            $table->string('params')->nullable();
            $table->text('description');
            $table->string('address');
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
