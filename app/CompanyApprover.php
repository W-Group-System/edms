<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyApprover extends Model
{
    //
    protected $table = 'company_approvers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'company_id',
        'user_id',
        'level'
    ];
}
