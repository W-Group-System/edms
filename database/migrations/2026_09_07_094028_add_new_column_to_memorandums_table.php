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
            if (!Schema::hasColumn('memorandums', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable();
            }

            if (!Schema::hasColumn('memorandums', 'remarks')) {
                $table->string('remarks')->nullable();
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
            if (Schema::hasColumn('memorandums', 'company_id')) {
                $table->dropColumn('company_id');
            }

            if (Schema::hasColumn('memorandums', 'remarks')) {
                $table->dropColumn('remarks');
            }

          
        });
    }
}
