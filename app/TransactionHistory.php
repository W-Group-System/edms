<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'transaction_histories';
    protected $fillable = [
        'id',
        'transaction_no',
        'action',
        'action_by',
        'remarks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'action_by', 'id');
    }
    public function prr()
    {
        // transaction_no on this table matches reference_num on the process review request.
        return $this->belongsTo(ProcessReviewRequest::class, 'transaction_no', 'reference_num');
    }
    
}
