<?php

namespace App\Services\Service;

use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use App\Services\FileUploadService; 


class ServiceService
{

    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
 


    public function index($request)
    {
        $perPage = $request->get('per_page', 10);

        $query = Service::query();

        // Search by service name
        if ($request->filled('service_name')) {
            $query->where('service_name', 'like', '' . $request->service_name . '%');
        }

        // Search by amount
        if ($request->filled('amount')) {
            $query->where('amount', $request->amount);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function store($request)
    {
        $data = $request->only([
            'service_name',
            'description',
            'amount'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUploadService->uploadImage(
                $request->file('image'),
                'services'
            );
        }


        $data['created_by'] = Auth::id();

        return Service::create($data);
    }

    public function update($request, $id)
    {
        $service = Service::findOrFail($id);

        $data = $request->only([
            'service_name',
            'description',
            'amount'
        ]);

        if ($request->hasFile('image')) {

            $data['image'] = $this->fileUploadService->replaceFile(
                $service->image,
                $request->file('image'),
                'services'
            );
        }

        $data['updated_by'] = Auth::id();

        $service->update($data);

        return $service;
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image) {
            $this->fileUploadService->deleteFile($service->image);
        }

        $service->delete();

        return true;
    } 

    public function deleteMultiple($request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            throw new \Exception('Invalid service IDs');
        }   
        
        $services = Service::whereIn('id', $ids)->get();
        foreach ($services as $service) {
            if ($service->image) {
                $this->fileUploadService->deleteFile($service->image);
            }
        }

        Service::whereIn('id', $ids)->delete();

        return true;
    }
}