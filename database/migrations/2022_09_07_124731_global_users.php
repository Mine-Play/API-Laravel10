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
        Schema::create('Users', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->uuid('wid')->nullable()->unique();
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->integer('role')->default(1);
            $table->string('status')->default("online");
        });
    }
    public function down()
    {
       Schema::dropIfExists('users');
    }
};