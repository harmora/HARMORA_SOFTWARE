<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyBonDeCommandeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
-
        // Recreate the bon_de_commande table with different columns
        Schema::create('bon_de_commande', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->unsignedBigInteger('fournisseur_id')->nullable();  // Foreign key for fournisseur
            $table->unsignedBigInteger('entreprise_id')->nullable();   // Foreign key for entreprise
            $table->string('type_achat');  // Type of purchase
            $table->decimal('montant', 15, 2);  // Montant (amount)
            $table->string('reference')->nullable();  // Reference number
            $table->date('date_commande')->nullable();
            $table->decimal('montant_ht', 15, 2)->nullable();
            $table->string('bon')->nullable();
            $table->enum('status', ['validated', 'pending', 'cancelled'])->default('pending');  // Status of the bon de commande
            $table->timestamps();  // created_at and updated_at timestamps


            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('set null');
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
        // Drop the table if you rollback the migration
        Schema::dropIfExists('bon_de_commande');
    }
}
