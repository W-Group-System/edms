<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class MajorProcess extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function policies()
    {
        return $this->hasMany(Policy::class, 'process_id', 'id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'policy_id', 'id');
    }
    
}
