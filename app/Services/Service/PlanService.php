<?php

namespace App\Services\Service;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use App\Services\FileUploadService; 


class PlanService
{

    // List Plans
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }


    public function index($request)
    {
        $perPage = $request->get('per_page',10);

        $query = Plan::with('services');

        if($request->filled('plan_name')){
            $query->where('plan_name','like',''.$request->plan_name.'%');
        }

        if($request->filled('amount')){
            $query->where('amount',$request->amount);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }


    // Show Single Plan
    public function show($id)
    {
        return Plan::with('services')->findOrFail($id);
    }


    // Create Plan
    public function store($request)
    {

        $data = $request->only([
            'plan_name',
            'description',
            'amount',
            'discount_percent'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUploadService->uploadImage(
                $request->file('image'),
                'plans'
            );
        }


        $data['created_by'] = Auth::id();

        $plan = Plan::create($data);

        // attach services
        if($request->services){
            $plan->services()->sync($request->services);
        }

        return $plan->load('services');
    }


    // Update Plan
    public function update($request,$id)
    {

        $plan = Plan::findOrFail($id);

        $data = $request->only([
            'plan_name',
            'description',
            'amount',
            'discount_percent'
        ]);

        if ($request->hasFile('image')) {

            $data['image'] = $this->fileUploadService->replaceFile(
                $plan->image,
                $request->file('image'),
                'plans'
            );
        }

        $data['updated_by'] = Auth::id();

        $plan->update($data);

        if($request->services){
            $plan->services()->sync($request->services);
        }

        return $plan->load('services');
    }


    // Delete Single
    public function delete($id)
    {
        $plan = Plan::findOrFail($id);
        if ($plan->image) {
            $this->fileUploadService->deleteFile($plan->image);
        }
        $plan->delete();
    }


    // Delete Multiple
    public function deleteMultiple($request)
    {
        $ids = $request->ids;

        $plans = Plan::whereIn('id', $ids)->get();

        foreach ($plans as $plan) {
            if ($plan->image) {
                $this->fileUploadService->deleteFile($plan->image);
            }
        }
        
        Plan::whereIn('id',$ids)->delete();
    }

}