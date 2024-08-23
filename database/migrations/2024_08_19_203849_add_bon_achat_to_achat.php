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
        Schema::table('achats', function (Blueprint $table) {
            $table->string('bon_achat')->nullable();
            $table->decimal('montant_restant', 15, 2)->nullable();
            $table->decimal('montant_payée', 15, 2)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            //
            $table->dropColumn('bon_achat');
            $table->dropColumn('montant_restant');
            $table->dropColumn('montant_payée');
        });
    }
};
