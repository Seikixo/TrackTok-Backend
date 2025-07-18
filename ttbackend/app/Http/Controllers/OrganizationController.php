<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

   public function __construct(
    private OrganizationRepository $organizationRepository
   ){}
    
    public function index(Request $request)
    {
        $organizations = $this->organizationRepository->getOrganizations($request->all());

        return response()->json([
            'success' => true,
            'message' => $organizations->isEmpty() ? 'No organization found.' : 'Organizations fetched successfully.',
            'organizations' => $organizations
        ], 200);
    }

    public function store(OrganizationRequest $request)
    {
        $validatedData = $request->validated();
        $this->organizationRepository->createOrganization($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Organization created successfully.',
        ], 201);
    }

    public function update(OrganizationRequest $request, $id)
    {
        $validatedData = $request->validated();
        $updatedOrganization = $this->organizationRepository->updateOrganization($validatedData, $id);

        return response()->json([
            'success' => true,
            'message' => 'Organization updated successfully.',
            'organizations' => $updatedOrganization
        ], 200);        
    }

    public function destroy($id)
    {
        $this->organizationRepository->deleteOrganization($id);

        return response()->json([
            'success' => true,
            'message' => 'Organization deleted successfully.',
        ], 200);        
    }
}
