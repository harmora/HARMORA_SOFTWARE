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
        Schema::create('regelements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(column: 'client_id')->nullable(); // Foreign key for clients table
            $table->unsignedBigInteger(column: 'facture_id')->nullable(); // Foreign key for clients table
            $table->unsignedBigInteger('entreprise_id')->nullable(); // Foreign key for users table
            $table->string('mode_virement')->nullable();
            $table->decimal('total_amount', 20, places: 2)->default(value: 0.00); // Total amount of the command
            $table->decimal('amount_payed', 20, places: 2)->default(value: 0.00); // Total amount of the command
            $table->decimal('remaining_amount', 20, places: 2)->default(value: 0.00); // Total amount of the command
            
            
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('facture_id')->references('id')->on('factures')->onDelete('set null');
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regelements');
    }
};
