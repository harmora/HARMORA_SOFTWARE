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
        Schema::table('disponibilities', function (Blueprint $table) {
            $table->dropColumn('reservation_date');

            // Add the new columns
            $table->timestamp('start_date_time')->nullable()->after('details');
            $table->timestamp('end_date_time')->nullable()->after('start_date_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disponibilities', function (Blueprint $table) {
            //
        });
    }
};
