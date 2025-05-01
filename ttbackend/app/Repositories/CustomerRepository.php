<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerRepository{

    public function getCustomers(string|null $search = null, int $perPage = 10) {
        $query = DB::table('customers');

        if($search)
        {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        return $query->paginate($perPage);
    }

    public function createCustomer(array $data) {
        return Customer::create($data);
    }

}