<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProcessReviewRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_review_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_num')->nullable();
            $table->string('control_code')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('document_id')->nullable();
            $table->string('request_type')->nullable();
            $table->string('effective_date')->nullable();
            $table->string('type_of_document')->nullable();
            $table->string('type_of_request')->nullable();
            $table->string('document_title')->nullable();
            $table->text('document_description')->nullable();
            $table->string('reason_of_request')->nullable();
            $table->string('supporting_documents')->nullable();
            // $table->string('reference_num')->nullable()->unique();
            $table->text('link_draft')->nullable();
            $table->string('report_draft_link')->nullable();
            $table->string('report_attachment')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_review_requests');
    }
}
