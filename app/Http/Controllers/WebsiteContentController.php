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

        if ($request->hasFile('why_choose_image')) {
            $path = $request->file('why_choose_image')->store('website_images', 'public');
            $data['why_choose_image'] = '/storage/' . $path;
        }

        // Handle JSON fields decoding correctly if sent via FormData
        // We do this first so we have the array structures ready
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

        // Handle Testimonial Images (sent as separate files)
        // Now that we've decoded the testimonials JSON into $data['testimonials'] (if it was sent)
        // We can attach any uploaded files to their respective objects
        if (isset($data['testimonials']) && is_array($data['testimonials'])) {
            $testimonials = $data['testimonials'];
            foreach ($testimonials as $index => &$testimonial) {
                // Check if a file was uploaded for this index
                $fileKey = "testimonial_image_" . $index;
                if ($request->hasFile($fileKey)) {
                    $path = $request->file($fileKey)->store('testimonials', 'public');
                    // Add/Update the image key in the testimonial object
                    $testimonial['image'] = '/storage/' . $path;
                }
            }
            // Update the data array with the modified testimonials
            $data['testimonials'] = $testimonials;
        }

        // Cleanup: Remove any temporary file keys (like testimonial_image_*) that are not DB columns
        // This prevents "Column not found" errors during save() since model guards are empty
        foreach (array_keys($data) as $key) {
            if (str_starts_with($key, 'testimonial_image_')) {
                unset($data[$key]);
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
