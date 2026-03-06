<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteContent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'header_nav' => 'array',
        'sub_nav' => 'array',
        'our_services' => 'array',
        'footer_links' => 'array',
        'footer_social_links' => 'array',
        'why_choose_us' => 'array',
        'trusted_partners' => 'array',
        'testimonials' => 'array'
    ];
}
