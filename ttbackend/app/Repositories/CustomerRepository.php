<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerRepository
{

    public function getCustomers(array $params = []) 
    {
        $query = Customer::query()
            ->with('appointments');

        if (!empty($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['sort_by'])) {
            $query->orderBy($params['sort_by'], $params['sort_order'] ?? 'asc');
        }

        if (!empty($params['per_page'])) {
            return $query->paginate($params['per_page'] ?? 10);
        }

        return $query->get();
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