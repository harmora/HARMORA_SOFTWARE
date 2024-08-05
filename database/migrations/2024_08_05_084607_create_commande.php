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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('client_id'); // Foreign key for clients table
            $table->unsignedBigInteger('user_id'); // Foreign key for users table
            $table->string('title'); // Title of the command
            $table->longText('description')->nullable(); // Description of the command
            $table->date('start_date'); // Start date of the command
            $table->date('due_date'); // Due date of the command
            $table->decimal('total_amount', 10, 2)->default(0.00); // Total amount of the command
            $table->string('status')->default('pending'); // Status of the command
            $table->timestamps(); // Created at and updated at timestamps

            // Define foreign key constraints
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
