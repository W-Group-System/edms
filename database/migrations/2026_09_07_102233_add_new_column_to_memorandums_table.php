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
            //
            if(!Schema::hasColumn('memorandums', 'final_status')) {
                $table->string('final_status')->nullable();
            }

            if(!Schema::hasColumn('memorandums', 'approved_by')) {
                $table->string('approved_by')->nullable();
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
        Schema::table('memorandums', function (Blueprint $table) {
            //
            if(Schema::hasColumn('memorandums', 'final_status')) {
                $table->dropColumn('final_status');
            }

            if(Schema::hasColumn('memorandums', 'approved_by')) {
                $table->dropColumn('approved_by');
            }
        });
    }
}
