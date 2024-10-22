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
            $table->unsignedBigInteger(column: 'achat_id')->nullable(); // Foreign key for clients table
            $table->unsignedBigInteger('entreprise_id')->nullable(); // Foreign key for users table
            $table->unsignedBigInteger(column: 'invoice_vente_id')->nullable(); // Foreign key for clients table


            $table->enum('mode_virement', ['espece', 'cheque', 'virement'])->default(value: 'espece');
            $table->decimal('amount_payed', 20, places: 2)->default(value: 0.00); // Total amount of the command
            $table->decimal('remaining_amount', total: 20, places: 2)->default(value: 0.00); // Total amount of the command
            $table->date('date');
            $table->enum('origin',['commande','achat']);
        
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('set null');
            $table->foreign('achat_id')->references('id')->on('achats')->onDelete('set null');
            $table->foreign('invoice_vente_id')->references('id')->on('invoices')->onDelete('set null');
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
