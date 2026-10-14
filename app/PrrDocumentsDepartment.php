<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrrDocumentsDepartment extends Model
{
    //

    protected $table = "prr_documents_departments";
    protected $primaryKey = 'id';
    protected $fillable = [
        "id",
        "department_id",
        "prr_document_id",
    ];

    public function prr_department()
    {
        return $this->belongsTo(Department::class);
    }

    public function prrDocDepartment()
    {
        return $this->belongsTo(ProcessReviewRequest::class, 'prr_document_id');
    }


}
