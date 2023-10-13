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
            $table->uuid('id')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->uuid('role');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('password_reset')->useCurrent();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->integer('skin')->default(0);
            $table->integer('cloak')->default(0);
            $table->integer('avatar')->default(0);
            $table->integer('banner')->default(0);
            $table->integer('level')->default(1);
            $table->integer('exp')->default(0);
            $table->string('totp')->nullable();
            $table->string('referal')->nullable();
            $table->json('params')->nullable();
        });
    }
    public function down()
    {
       Schema::dropIfExists('users');
    }
};