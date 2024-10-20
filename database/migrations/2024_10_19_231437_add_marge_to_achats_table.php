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
        Schema::table('achats', function (Blueprint $table) {
            // Adding the 'marge' column with decimal type (e.g., 8 digits total, 2 digits after decimal)
            $table->decimal('marge', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            // Dropping the 'marge' column if the migration is rolled back
            $table->dropColumn('marge');
        });
    }
};
