<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrrDocumentsCompany extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = "prr_documents_companies";
    protected $fillable = [
        "id",
        "company_id",
        "prr_document_id",
    ];

    public function prr_company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function prrDocCompany()
    {
        return $this->belongsTo(ProcessReviewRequest::class, 'prr_document_id');
    }
}
