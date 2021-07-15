<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCollection extends Model
{
    use HasFactory;

    protected $dates =  [
        'deleted_at'
    ];

    protected $fillable = [
        'paynumber',
        'jobcard',
        'issue_date',
        'allocation',
        'frequest',
        'done_by',
        'status',
        'collected_by',
        'id_number',
        'self'
    ];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class,'allocation','allocation');
    }

    public function frequest()
    {
        return $this->belongsTo(FoodRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'paynumber','paynumber');
    }

    public function job()
    {
        return $this->belongsTo(Jobcard::class,'card_number','jobcard');
    }

}
