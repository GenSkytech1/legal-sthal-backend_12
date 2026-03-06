<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanySetting;

class CompanySettingController extends Controller
{
    public function index()
    {
        $setting = CompanySetting::first();
        return response()->json([
            "status" => "success",
            "data" => $setting ?: (object)[]
        ]);
    }

    public function store(Request $request)
    {
        $setting = CompanySetting::first() ?: new CompanySetting();

        $filesToUpload = ["company_icon", "favicon", "company_logo", "company_dark_logo"];
        foreach ($filesToUpload as $fileField) {
            if ($request->hasFile($fileField)) {
                $path = $request->file($fileField)->store("company_images", "public");
                $setting->{$fileField} = "/storage/" . $path;
            }
        }

        $fields = [
            "company_name", "company_email", "phone_number", "fax", 
            "website", "address", "country", "state", "city", "postal_code"
        ];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $setting->{$field} = $request->input($field);
            }
        }

        $setting->save();

        return response()->json([
            "status" => "success",
            "message" => "Company settings saved successfully",
            "data" => $setting
        ]);
    }
}

