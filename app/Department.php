<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Department extends Model  implements Auditable
{

    use \OwenIt\Auditing\Auditable;
    //

    public function dep_head()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function permit_account()
    {
        return $this->hasOne(User::class, 'id', 'permit_accountable');
    }
}
