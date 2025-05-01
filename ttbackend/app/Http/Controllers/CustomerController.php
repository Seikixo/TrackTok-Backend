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
        try{
            $customers = $this->customerRepository->getCustomers($request->input('search'));
            return response()->json([
                'success' => true,
                'customers' => $customers
            ]);
        }
        catch(QueryException $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while retrieving customers',
                'error' => $e->getMessage()
            ], 500);
        }
        catch(Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customers.',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    public function create()
    {

    }

    public function store(CustomerRequest $request)
    {
        try
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
        catch(QueryException $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage(), 
            ], 500);
        }
        catch(Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
