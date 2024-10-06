<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            // Defining the columns
            $table->bigIncrements('id');
            $table->unsignedBigInteger('commande_id');
            $table->string('commande_name', 255);
            $table->enum('payement_state', ['payed', 'unpaid']);
            $table->date('date_facture');
            $table->unsignedBigInteger('entreprise_id')->nullable(); // Foreign key for users table

            $table->timestamps();

            // Defining the indexes
            $table->index('commande_id');


            // Adding the foreign key constraint
            $table->foreign('commande_id')
                  ->references('id')
                  ->on('commandes')
                  ->onDelete('cascade');
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('set null');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            // Dropping the foreign key constraint
            $table->dropForeign(['commande_id']);
        });

        Schema::dropIfExists('factures');
    }
}




