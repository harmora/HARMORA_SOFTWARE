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
            // Adding the 'payment_type' column with enum type
            $table->enum('payment_type', ['Virement', 'Chèque', 'Espèce'])->default('Virement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            // Dropping the 'payment_type' column if the migration is rolled back
            $table->dropColumn('payment_type');
        });
    }
};
