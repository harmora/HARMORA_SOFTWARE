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
        Schema::create('mouvements_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('achat_id')->nullable();
            $table->unsignedBigInteger('commande_id')->nullable();
            $table->timestamps();
            $table->enum('type_mouvement', ['entrée', 'sortie']);
            $table->string('reference');
            $table->string('description')->nullable();
            $table->integer('quantitéajoutée');
            $table->integer('quantitéprecedente');
            $table->dateTime('date_mouvement');   
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('achat_id')->references('id')->on('achats')->onDelete('set null');
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements_stocks');
    }
};
