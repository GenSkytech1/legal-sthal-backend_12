<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Service\ServiceService;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    // Get All Services
    public function index(Request $request)
    {

        $data = $this->serviceService->index($request);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // Create Service
    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0', 
            'amount' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048000'
        ]);



        $service = $this->serviceService->store($request);

        return response()->json([
            'status' => true,
            'message' => 'Service Created',
            'data' => $service
        ]);
    }

    // Update Service
    public function update(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048000'
        ]);

        $service = $this->serviceService->update($request, $id);
       
        return response()->json([
            'status' => true,
            'message' => 'Service Updated',
            'data' => $service
        ]);
    }

    // Delete Service
    public function destroy($id)
    {
        $this->serviceService->delete($id);

        return response()->json([
            'status' => true,
            'message' => 'Service Deleted'
        ]);
    } 

    public function deleteMultiple(Request $request)
    {
        $this->serviceService->deleteMultiple($request);

        return response()->json([
            'status' => true,
            'message' => 'Services Deleted Successfully'
        ]);
    }
}