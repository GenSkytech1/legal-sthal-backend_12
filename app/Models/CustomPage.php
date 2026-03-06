<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'hero_title',
        'hero_price',
        'hero_subtitle',
        'also_get',
        'form_title',
        'how_it_works',
        'process_list',
        'benefits',
        'requirements',
        'documents',
        'fees_cost',
        'what_you_get'
    ];

    protected $casts = [
        'also_get' => 'array',
        'how_it_works' => 'array',
        'process_list' => 'array',
        'benefits' => 'array',
        'requirements' => 'array',
        'documents' => 'array',
        'what_you_get' => 'array',
    ];
}
