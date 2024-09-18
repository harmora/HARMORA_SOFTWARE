<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('achats', function (Blueprint $table) {
           
            $table->id();
            $table->unsignedBigInteger('fournisseur_id')->nullable();
            $table->unsignedBigInteger('entreprise_id')->nullable();
            $table->string('type_achat');
            $table->decimal('montant', 15, 2);
            $table->string('status_payement')->default('unpaid');
            $table->decimal('tva', 5, 2)->default(20);
            $table->string('facture')->nullable();
            $table->date('date_paiement')->nullable();
            $table->date('date_limit')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('set null');
            $table->foreign(columns: 'fournisseur_id')->references('id')->on('fournisseurs')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};
