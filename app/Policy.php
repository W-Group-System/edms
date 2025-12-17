<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

    public function annexes()
    {
        return $this->hasMany(Annex::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'policy_id', 'id');
    }
    
}
