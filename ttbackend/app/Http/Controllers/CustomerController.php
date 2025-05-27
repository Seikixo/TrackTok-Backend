<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Repositories\CustomerRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class CustomerController extends Controller
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $customers = $this->customerRepository->getCustomers($request->all());
        
        return response()->json([
            'success' => true,
            'message' => $customers->isEmpty() ? 'No customers found.' : 'Customers fetched successfully.',
            'customers' => $customers
        ], 200);
    }


    public function store(CustomerRequest $request)
    {
        $validatedData = $request->validated();
        
        $this->customerRepository->createCustomer([
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'contact_number' => $validatedData['contact_number'],
            'email' => $validatedData['email']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, $id)
    {
        $validatedData = $request->validated();

        $updatedCustomer = $this->customerRepository->updateCustomer($id, [
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'contact_number' => $validatedData['contact_number'],
            'email' => $validatedData['email']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'updatedCustomer' => $updatedCustomer,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->customerRepository->deleteCustomer($id);

        return response()->json([
            'status' => true,
            'message' => 'Customer deleted successfully.'
        ], 200);
    }
}
