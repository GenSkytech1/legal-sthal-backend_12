<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'source',
        'platform_lead_id',
        'campaign_id',
        'campaign_name',
        'ad_id',
        'ad_name',
        'form_id',
        'name',
        'email',
        'phone',
        'city',
        'service_type',
        'message',
        'status',
        'assigned_to',
        'custom_fields',
        'raw_payload'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'raw_payload' => 'array'
    ];
}
