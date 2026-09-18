<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class AccountRequest extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
     public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
