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
        Schema::create('depots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('city');
            $table->string('country');
            $table->unsignedBigInteger(column: 'entreprise_id')->nullable();   // Foreign key for entreprise

            $table->timestamps();
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('set null');

        });

        Schema::create('depot_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('depot_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();

            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');

            $table->unique(['depot_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depot_product');
        Schema::dropIfExists('depots');
    }
};