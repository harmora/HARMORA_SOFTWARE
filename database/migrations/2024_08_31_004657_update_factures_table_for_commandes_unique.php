<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFacturesTableForCommandesUnique extends Migration
{
    public function up()
    {
        Schema::table('factures', function (Blueprint $table) {
            if (!Schema::hasColumn('factures', 'commande_id')) {
                $table->unsignedBigInteger('commande_id')->after('id');
            }
    
            if (!Schema::hasColumn('factures', 'commande_name')) {
                $table->string('commande_name', 255)->notNullable()->after('commande_id');
            }
    
            if (!Schema::hasColumn('factures', 'payement_state')) {
                $table->enum('payement_state', ['payed', 'unpaid'])->default('unpaid')->notNullable()->after('commande_name');
            }
        });
    }
    

    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['commande_id']);
            $table->dropColumn(['commande_id', 'commande_name', 'payement_state']);
        });
    }
}
