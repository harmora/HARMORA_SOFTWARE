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
        Schema::create('devises', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('client_id')->nullable(); // Foreign key for clients table
            $table->unsignedBigInteger('user_id')->nullable(); // Foreign key for users table
            $table->unsignedBigInteger('entreprise_id')->nullable(); // Foreign key for users table
            $table->string('title'); // Title of the command
            $table->longText('description')->nullable(); // Description of the command
            $table->date('start_date'); // Start date of the command
            $table->date('due_date'); // Due date of the command
            $table->decimal('total_amount', 20, places: 2)->default(value: 0.00); // Total amount of the command
            $table->string('status')->default('pending'); // Status of the command pending/complited
            $table->timestamps(); // Created at and updated at timestamps

            // Define foreign key constraints
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devises');
    }
};
