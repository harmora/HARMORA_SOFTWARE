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
        Schema::table('products', function (Blueprint $table) {
            //
            $table->unsignedinteger('total_amount')->nullable();
            $table->unsignedinteger('prev_price')->nullable();
            $table->unsignedinteger('prev_stock')->nullable();
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropColumn('total_amount');
            $table->dropColumn('prev_price');
            $table->dropColumn('prev_stock');
        });
    }
};
