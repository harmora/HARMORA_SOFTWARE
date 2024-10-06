<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->foreignId('pack_id')->nullable()->constrained('packs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropForeign(['pack_id']);
            $table->dropColumn('pack_id');
        });
    }
};
