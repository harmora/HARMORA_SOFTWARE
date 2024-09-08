<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntrepriseIdToCommandesTable extends Migration
{
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Add the entreprise_id column
            $table->unsignedBigInteger('entreprise_id')->nullable()->after('id');

            // Define the foreign key constraint
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Drop the foreign key constraint and the column
            $table->dropForeign(['entreprise_id']);
            $table->dropColumn('entreprise_id');
        });
    }
}
