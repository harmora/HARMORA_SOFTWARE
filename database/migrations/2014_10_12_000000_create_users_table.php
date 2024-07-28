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

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('country_code', 28)->nullable();
            $table->string('phone', 56)->nullable();
            $table->string('email', 191)->unique();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip', 56)->nullable();
            $table->string('password');
            $table->date('dob')->nullable();
            $table->date('doj')->nullable();
            $table->string('photo')->nullable();
            $table->string('avatar')->default('avatar.png');
            $table->tinyInteger('active_status')->default(0)->comment('notsure');
            $table->tinyInteger('dark_mode')->default(0);
            $table->string('messenger_color')->nullable();
            $table->string('lang', 28)->default('en');
            $table->text('remember_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->tinyInteger('status')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
