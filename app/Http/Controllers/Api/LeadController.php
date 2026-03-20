<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * Accessible by public (external form).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'serviceType' => 'nullable|string',
            'message' => 'nullable|string',
            'city' => 'nullable|string',
            'source' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            
            // Map frontend fields to DB columns if names differ
            $leadData = [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'service_type' => $data['serviceType'] ?? null, // Map serviceType to service_type
                'message' => $data['message'] ?? null,
                'city' => $data['city'] ?? null,
                'source' => $data['source'] ?? 'Website',
                'raw_payload' => $data // Store the full request just in case
            ];

            // Add title to name if present (e.g. "Mr. John Doe")
            if (isset($data['title']) && !empty($data['title'])) {
                // Check if name already starts with title to avoid duplication
                if (!str_starts_with($leadData['name'], $data['title'])) {
                    // It seems the frontend sends "Mr. John Doe" in the name field already?
                    // Let's check the frontend code provided:
                    // name: formData.title + ' ' + formData.name
                    // So title is already prepended to name. We can ignore it or store separately in custom_fields.
                }
            }

            $lead = Lead::create($leadData);

            return response()->json([
                'status' => 'success',
                'message' => 'Inquiry submitted successfully',
                'data' => $lead
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit inquiry: ' + $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     * Accessible by admin (protected).
     */
    // public function index()
    // {
    //     $leads = Lead::orderBy('created_at', 'desc')->get();
    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $leads
    //     ]);
    // }
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // default 10

        $leads = Lead::orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $leads->items(), // actual data
            'pagination' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
            ]
        ]);
    }
}
