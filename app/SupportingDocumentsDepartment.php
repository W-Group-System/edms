<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportingDocumentsDepartment extends Model
{
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
