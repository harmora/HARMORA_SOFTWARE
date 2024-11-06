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
        Schema::table('vente_products', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('depot_id')->nullable()->after('product_id');
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vente_products', function (Blueprint $table) {
            //
            $table->dropForeign(['depot_id']);
            $table->dropColumn('depot_id');
        });
    }
};
