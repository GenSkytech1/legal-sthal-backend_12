<?php

namespace App\Http\Controllers;

use App\Models\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteContentController extends Controller
{
    public function index()
    {
        $websiteContent = WebsiteContent::first();
        if (!$websiteContent) {
            $websiteContent = WebsiteContent::create([
                'header_nav' => [],
                'sub_nav' => [],
                'our_services' => [],
                'footer_links' => [],
                'footer_social_links' => []
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $websiteContent
        ]);
    }

    public function store(Request $request)
    {
        $websiteContent = WebsiteContent::first() ?: new WebsiteContent();

        $data = $request->except(['header_logo', 'hero_image']);

        if ($request->hasFile('header_logo')) {
            $logoPath = $request->file('header_logo')->store('website_images', 'public');
            $data['header_logo'] = '/storage/' . $logoPath;
        }

        if ($request->hasFile('hero_image')) {
            $heroPath = $request->file('hero_image')->store('website_images', 'public');
            $data['hero_image'] = '/storage/' . $heroPath;
        }

        // Handle JSON fields decoding correctly if sent via FormData
        $jsonFields = ['header_nav', 'sub_nav', 'our_services', 'footer_links', 'footer_social_links', 'why_choose_us', 'trusted_partners', 'testimonials'];
        foreach ($jsonFields as $field) {
            if ($request->has($field)) {
                $val = $request->input($field);
                if (is_string($val)) {
                    $val = json_decode($val, true) ?? [];
                }
                $data[$field] = $val;
            }
        }

        $websiteContent->fill($data);
        $websiteContent->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Website content saved successfully',
            'data' => $websiteContent
        ]);
    }
}
