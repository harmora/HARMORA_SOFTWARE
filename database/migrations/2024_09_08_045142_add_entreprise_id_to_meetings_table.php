<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->unsignedBigInteger('entreprise_id')->nullable(); // Adding the column
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade'); // Foreign key constraint
        });
    }

    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropForeign(['entreprise_id']); // Dropping the foreign key
            $table->dropColumn('entreprise_id');    // Dropping the column
        });
    }

};
