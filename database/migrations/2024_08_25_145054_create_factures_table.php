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
        Schema::create('factures', function (Blueprint $table) {
            $table->id(); // This creates an auto-incrementing BIGINT UNSIGNED column called 'id'
            $table->string('company_name');
            $table->text('address');
            $table->text('contact_details');
            $table->string('email');
            $table->date('date');
            $table->string('invoice_number');
            $table->string('logo')->nullable();
            $table->string('client_name');
            $table->text('client_address');
            $table->text('client_contact_details');
            $table->text('item_description');
            $table->unsignedInteger('item_quantity');
            $table->decimal('item_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('tax_rate', 5, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->timestamps(); // This creates 'created_at' and 'updated_at' TIMESTAMP columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
