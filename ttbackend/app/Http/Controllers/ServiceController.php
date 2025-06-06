<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $services = $this->serviceRepository->getServices($request->all());

        return response()->json([
            'success' => true,
            'message' => $services->isEmpty() ? 'No services found.' : 'Services fetched successfully.',
            'services' => $services
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        $validatedData = $request->validated();
        $this->serviceRepository->createService($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, $id)
    {
        $validatedData = $request->validated();

        $updatedService = $this->serviceRepository->updateService($id, $validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully.',
            'service' => $updatedService
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deletedService = $this->serviceRepository->deleteService($id);

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully.',
            'service' => $deletedService
        ], 200);
    }
}
