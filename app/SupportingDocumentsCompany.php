<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportingDocumentsCompany extends Model
{
    //

   public function company()
    {
        return $this->belongsTo(
            Company::class,
            'company_id'
        );
    }

    public function supportingDocument()
    {
        return $this->belongsTo(
            SupportingDocument::class,
            'supporting_document_id'
        );
    }
}
