<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerRepository{

    public function getCustomers(string|null $search = null, int $perPage = 10, ?string $sortBy = 'name', string $sortOrder = 'asc') 
    {
        $query = DB::table('customers');

        if($search)
        {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        return $query->orderBy($sortBy, $sortOrder)
                ->paginate($perPage);
    }

    public function createCustomer(array $data) 
    {
        return Customer::create($data);
    }

    public function updateCustomer($id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);

        return $customer;
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        
        return $customer->delete();
    }

}