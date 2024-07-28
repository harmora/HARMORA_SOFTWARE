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
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('denomination'); // Dénomination sociale de l'entreprise
            $table->unsignedBigInteger('forme_juridique_id')->nullable(); // Forme juridique de l'entreprise
            $table->unsignedBigInteger('ICE')->nullable(); // Identifiant Commun de l'Entreprise (ICE)
            $table->unsignedBigInteger('IF')->nullable(); // Identifiant Fiscal (IF)
            $table->unsignedBigInteger('RC')->nullable(); // Registre de Commerce (RC)
            $table->string('address'); // Address of the enterprise
            $table->timestamps();

            // Define the foreign key relationship
            $table->foreign('forme_juridique_id')->references('id')->on('forme_juridiques')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
