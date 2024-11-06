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
        Schema::table('invoices', function (Blueprint $table) {
            //
            $table->string('reference_num')->nullable()->after('id'); // or choose a different position

        });
        Schema::table('devises', function (Blueprint $table) {
            //
            $table->string('reference_num')->nullable()->after('id'); // or choose a different position

        });
        Schema::table('bon_livraisions', function (Blueprint $table) {
            //
            $table->string('reference_num')->nullable()->after('id'); // or choose a different position

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
            $table->dropColumn('reference_num');
        });
        Schema::table('devises', function (Blueprint $table) {
            //
            $table->dropColumn('reference_num');
        });
        Schema::table('bon_livraisions', function (Blueprint $table) {
            //
            $table->dropColumn('reference_num');
        });
    }
};
