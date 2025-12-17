<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Process extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
    public function major_processes()
    {
        return $this->hasMany(MajorProcess::class, 'process_id', 'id')
                    ->WhereNull('status');
    }

    
}
