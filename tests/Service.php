<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_name',
        'description',
        'amount',
        'discount_percentage', 
        'image',
        'created_by',
        'updated_by'
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class,'plan_service');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    } 
    
    // Updator
    public function updator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    } 

}