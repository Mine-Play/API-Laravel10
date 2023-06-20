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
        Schema::create('Wallets', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->integer('money')->default(0);
            $table->integer('coins')->default(0);
            $table->integer('keys')->default(0);
            $table->json('history')->nullable();
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