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
        Schema::create('vente_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('related_id');  // ID of either a 'devis', 'facture', or 'bon_livraison'
            $table->string('related_type');            // Will store 'devis', 'facture', or 'bon_livraison'
            $table->integer('quantity')->nullable();   // Example additional field
            $table->decimal('price', 10, places: 2); // Price of the product in the command

            $table->timestamps();
            
            // Foreign keys and indexing
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index(['related_id', 'related_type']); // Index for polymorphic relationship

        });
        
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vente_products');
    }
};
