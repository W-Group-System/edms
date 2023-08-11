<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class RequestApprover extends Model  implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function change_request()
    {
        return $this->belongsTo(ChangeRequest::class);
    }
}
