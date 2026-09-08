<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToMemorandumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->string('company_id')->nullable();
            $table->string('remarks')->nullable();
            $table->string('final_status')->nullable();
            $table->string('approved_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->dropColumn(['company_id','remarks','final_status', 'approved_by',]);
        });
    }
}
