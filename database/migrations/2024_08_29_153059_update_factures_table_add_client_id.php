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
        Schema::table('factures', function (Blueprint $table) {
            // Check if the client_id column exists before adding it
            if (!Schema::hasColumn('factures', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('id');
            }
        });
    
        // Assign a default client_id to existing records
        DB::table('factures')->update(['client_id' => DB::table('clients')->first()->id]);
    
        Schema::table('factures', function (Blueprint $table) {
            // Add foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
    
            // Drop the redundant columns
            if (Schema::hasColumn('factures', 'client_name')) {
                $table->dropColumn(['client_name', 'client_address', 'client_contact_details']);
            }
    
            // Make the client_id column non-nullable
            $table->unsignedBigInteger('client_id')->nullable(false)->change();
        });
    }
    
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            //
                        // Reverse the changes made in the up() method

            // Add back the dropped columns
            $table->string('client_name');
            $table->text('client_address');
            $table->text('client_contact_details');

            // Drop the client_id column and foreign key
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};
