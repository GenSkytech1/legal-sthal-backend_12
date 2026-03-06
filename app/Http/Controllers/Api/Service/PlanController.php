<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Service\PlanService;

class PlanController extends Controller
{
    protected $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    // List Plans
    public function index(Request $request)
    {
        $data = $this->planService->index($request);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // Get Single Plan
    public function show($id)
    {
        $data = $this->planService->show($id);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // Create Plan
    public function store(Request $request)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048000'
        ]);

        $data = $this->planService->store($request);

        return response()->json([
            'status' => true,
            'message' => 'Plan Created Successfully',
            'data' => $data
        ]);
    }

    // Update Plan
    public function update(Request $request,$id)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048000'
        ]);    
    
        $data = $this->planService->update($request,$id);

        return response()->json([
            'status' => true,
            'message' => 'Plan Updated Successfully',
            'data' => $data
        ]);
    }

    // Delete Plan
    public function destroy($id)
    {
        $this->planService->delete($id);

        return response()->json([
            'status' => true,
            'message' => 'Plan Deleted Successfully'
        ]);
    }

    // Delete Multiple Plans
    public function deleteMultiple(Request $request)
    {
        $this->planService->deleteMultiple($request);

        return response()->json([
            'status' => true,
            'message' => 'Plans Deleted Successfully'
        ]);
    }
}