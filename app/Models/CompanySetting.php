<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    /** @use HasFactory<\Database\Factories\CompanySettingFactory> */
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_email',
        'phone_number',
        'fax',
        'website',
        'company_icon',
        'favicon',
        'company_logo',
        'company_dark_logo',
        'address',
        'country',
        'state',
        'city',
        'postal_code',
    ];
}
