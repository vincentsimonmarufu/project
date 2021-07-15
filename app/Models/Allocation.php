<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Allocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paynumber',
        'food_allocation',
        'meet_allocation',
        'meet_a',
        'meet_b',
        'allocation',
        'status'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'paynumber','paynumber');
    }

    public function fcollection()
    {
        return $this->hasOne(FoodCollection::class,'allocation','allocation');
    }

}
