<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTvaToBonDeCommandeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bon_de_commande', function (Blueprint $table) {
            $table->enum('tva', ['0', '7', '10', '14', '16', '20'])->default('0')->after('montant_ht'); // Add the tva column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bon_de_commande', function (Blueprint $table) {
            $table->dropColumn('tva'); // Drop the tva column if rolling back
        });
    }
}
