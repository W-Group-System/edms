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
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->string('visibility')->nullable();
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
               $table->dropColumn(['status','remarks','visibility']);
        });
    }
}
