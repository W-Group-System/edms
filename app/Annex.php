<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Annex extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
