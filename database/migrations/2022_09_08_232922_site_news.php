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
    protected $connection = 'Site';
    public function up()
    {
        Schema::create('News', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->string('title');
            $table->string('subtitle');
            $table->text('content');
            $table->uuid('author');
            $table->integer('time');
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->timestamp('date')->useCurrent();
            $table->json("vote")->nullable();
            $table->json("anchors")->nullable();
            $table->uuid("category");
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