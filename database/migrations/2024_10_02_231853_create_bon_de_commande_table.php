<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonDeCommandeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bon_de_commande', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->unsignedBigInteger('fournisseur_id')->nullable();  // Foreign key for fournisseur
            $table->unsignedBigInteger('entreprise_id')->nullable();   // Foreign key for entreprise
            $table->string('type_achat');  // Type of purchase
            $table->decimal('montant', 15, 2);  // Montant (amount)
            $table->enum('tva', ['0', '7', '10', '14', '16', '20'])->default('20');  // TVA with default 20.00
            $table->string('facture')->nullable();  // Facture number
            $table->date('date_paiement')->nullable();  // Payment date
            $table->date('date_limit')->nullable();  // Payment limit date
            $table->string('reference')->nullable();  // Reference number
            $table->decimal('montant_ht', 15, 2)->nullable();  // Montant HT (before tax)
            $table->string('bon_achat')->nullable();  // Bon achat
            $table->decimal('montant_restant', 15, 2)->nullable();  // Remaining amount
            $table->decimal('montant_payée', 15, 2)->nullable();  // Paid amount
            $table->string('devis')->nullable();  // Devis (estimate)
            $table->enum('status', ['validated', 'pending', 'cancelled'])->default('pending');  // Status of the bon de commande
            $table->timestamps();  // created_at and updated_at timestamps

            // Foreign Key Constraints
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
        Schema::dropIfExists('bon_de_commande');
    }
}
