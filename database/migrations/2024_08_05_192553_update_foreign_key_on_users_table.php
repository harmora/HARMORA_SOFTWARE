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
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing foreign key constraint if it exists
            $table->dropForeign(['entreprise_id']);
            
            // Modify the `entreprise_id` column to be nullable
            $table->unsignedBigInteger('entreprise_id')->nullable()->change();

            // Add the foreign key constraint with `onDelete('set null')`
            $table->foreign('entreprise_id')
                  ->references('id')
                  ->on('entreprises')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['entreprise_id']);
            
            // Modify the `entreprise_id` column to not be nullable
            $table->unsignedBigInteger('entreprise_id')->nullable(false)->change();
            
            // Add the original foreign key constraint with `onDelete('cascade')`
            $table->foreign('entreprise_id')
                  ->references('id')
                  ->on('entreprises')
                  ->onDelete('cascade');
        });
    }
};
