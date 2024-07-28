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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company')->nullable();
            $table->string('email', 191)->unique();
            $table->string('country_code', 28)->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->date('dob')->nullable();
            $table->date('doj')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();
            $table->string('photo');
            $table->tinyInteger('status')->default(0);
            $table->string('lang', 28)->default('en');
            $table->text('remember_token')->nullable();
            $table->tinyInteger('email_verification_mail_sent')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('acct_create_mail_sent')->default(1);
            $table->tinyInteger('internal_purpose')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
