<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plan_name',
        'description',
        'amount',
        'discount_percent',
        'amount_after_discount',
        'image',
        'created_by',
        'updated_by'
    ];

     /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function services()
    {
        return $this->belongsToMany(Service::class,'plan_service');
    } 

    // Creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    } 

     // Updator
    public function updator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    } 


    // Calculate Discount Amount
    public function calculateDiscountAmount()
    {
        return ($this->amount * $this->discount_percent) / 100;
    }



}