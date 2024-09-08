<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueIndexFromCommandeProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commande_products', function (Blueprint $table) {
            $table->dropUnique('commande_products_commande_id_product_id_unique'); // Replace with the correct index name if needed
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
            $table->unique(['commande_id', 'product_id']);
        });
    }
}
