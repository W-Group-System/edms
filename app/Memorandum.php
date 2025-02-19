<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Memorandum extends Model
{
    protected $table = 'memorandums';

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'uploaded_by');
    }
    public function memorandum_document()
    {
        return $this->hasMany(MemorandumDocument::class);
    }
}
