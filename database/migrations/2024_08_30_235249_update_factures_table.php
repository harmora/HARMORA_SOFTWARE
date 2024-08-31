<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFacturesTable extends Migration
{
    public function up()
    {
        Schema::table('factures', function (Blueprint $table) {
            // Drop unnecessary columns only if they exist
            if (Schema::hasColumn('factures', 'company_name')) {
                $table->dropColumn('company_name');
            }
            if (Schema::hasColumn('factures', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('factures', 'contact_details')) {
                $table->dropColumn('contact_details');
            }
            if (Schema::hasColumn('factures', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('factures', 'invoice_number')) {
                $table->dropColumn('invoice_number');
            }
            if (Schema::hasColumn('factures', 'logo')) {
                $table->dropColumn('logo');
            }
            if (Schema::hasColumn('factures', 'client_name')) {
                $table->dropColumn('client_name');
            }
            if (Schema::hasColumn('factures', 'client_address')) {
                $table->dropColumn('client_address');
            }
            if (Schema::hasColumn('factures', 'client_contact_details')) {
                $table->dropColumn('client_contact_details');
            }
            if (Schema::hasColumn('factures', 'item_description')) {
                $table->dropColumn('item_description');
            }
            if (Schema::hasColumn('factures', 'item_quantity')) {
                $table->dropColumn('item_quantity');
            }
            if (Schema::hasColumn('factures', 'item_price')) {
                $table->dropColumn('item_price');
            }
            if (Schema::hasColumn('factures', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('factures', 'tax_rate')) {
                $table->dropColumn('tax_rate');
            }
            if (Schema::hasColumn('factures', 'tax_amount')) {
                $table->dropColumn('tax_amount');
            }
            if (Schema::hasColumn('factures', 'grand_total')) {
                $table->dropColumn('grand_total');
            }

            // Add new columns
            $table->unsignedBigInteger('commande_id')->after('id');
            $table->enum('payement_state', ['payed', 'unpaid'])->after('commande_id');

            // Add foreign key constraint
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            // Add back the columns if you rollback
            $table->string('company_name', 255)->nullable();
            $table->text('address')->nullable();
            $table->text('contact_details')->nullable();
            $table->string('email', 255)->nullable();
            $table->date('date')->nullable();
            $table->string('invoice_number', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('client_name', 255)->nullable();
            $table->text('client_address')->nullable();
            $table->text('client_contact_details')->nullable();
            $table->text('item_description')->nullable();
            $table->unsignedInteger('item_quantity')->nullable();
            $table->decimal('item_price', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('grand_total', 10, 2)->nullable();

            // Drop the new columns
            $table->dropForeign(['commande_id']);
            $table->dropColumn(['commande_id', 'payement_state']);
        });
    }
}
