<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeysFromCommandeProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commande_products', function (Blueprint $table) {
            // Replace with your actual foreign key constraint names
            $table->dropForeign(['commande_id']);
            $table->dropForeign(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commande_products', function (Blueprint $table) {
            // Recreate the foreign keys if necessary
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
}
