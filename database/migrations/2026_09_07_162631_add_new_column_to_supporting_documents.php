<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToSupportingDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supporting_documents', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('supporting_documents', 'status')) {
                $table->string('status')
                    ->nullable()
                    ->after('uploaded_by');
            }

            if(!Schema::hasColumn('supporting_documents', 'approved_by')) {
                $table->string('remarks')
                    ->nullable()
                    ->after('status');
            }

            if(!Schema::hasColumn('supporting_documents', 'visibility')) {
                $table->string('visibility')
                    ->nullable()
                    ->after('approved_by');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supporting_documents', function (Blueprint $table) {
            //
            if(Schema::hasColumn('supporting_documents', 'status')) {
                $table->dropColumn('status');
            }

            if(Schema::hasColumn('supporting_documents', 'approved_by')) {
                $table->dropColumn('approved_by');
            }

            if(Schema::hasColumn('supporting_documents', 'visibility')) {
                $table->dropColumn('visibility');
            }
        });
    }
}
