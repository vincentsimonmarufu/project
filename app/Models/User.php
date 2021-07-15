<?php

namespace App\Models;

use App\Http\Controllers\FoodCollectionController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoleAndPermission, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'name',
        'paynumber',
        'first_name',
        'last_name',
        'mobile',
        'department_id',
        'usertype_id',
        'email',
        'password',
        'activated',
        'pin',
        'fcount',
        'mcount',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function usertype()
    {
        return $this->belongsTo(Usertype::class,'usertype_id','id');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class,'paynumber','paynumber');
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function frequests()
    {
        return $this->hasMany(FoodRequest::class,'paynumber','paynumber');
    }

    public function fcollections()
    {
        return $this->hasMany(FoodCollection::class,'paynumber','paynumber');
    }

}
