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
        Schema::create('achat_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('achat_id')->nullable();

            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->timestamps();
            // Foreign keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('achat_id')->references('id')->on('achats')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achat_products');
    }
};
