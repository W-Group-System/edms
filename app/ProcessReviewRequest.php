<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessReviewRequest extends Model
{
    //
    protected $table = 'process_review_requests';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'reference_num',
        'control_code',
        'company_id',
        'department_id',
        'user_id',
        'document_id',
        'request_type',
        'effective_date',
        'type_of_document',
        'type_of_request',
        'document_title',
        'document_description',
        'reason_of_request',
        'supporting_documents',
        'link_draft',
        'remarks',
        'status',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prr_document_company()
    {
        return $this->hasMany(PrrDocumentsCompany::class, 
                                'prr_document_id');
    }

    public function prr_document_dept()
    {
        return $this->hasMany(PrrDocumentsDepartment::class, 'prr_document_id');
    }

    public function transactionHistories()
    {
        // reference_num on this table matches transaction_no on transaction histories.
        return $this->hasMany(TransactionHistory::class, 'transaction_no', 'reference_num');
    }

    public function changeRequest()
    {
        return $this->hasMany(ChangeRequest::class, 'control_code', 'control_code');
    }

   
}
