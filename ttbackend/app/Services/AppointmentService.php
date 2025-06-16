<?php

namespace App\Services;

use App\Models\Service;
use Exception;

class AppointmentService
{

    public function __construct()
    {
        //
    }

    public function calculateTotalPriceOfService(array $service): float
    {
        try 
        {
            $price = Service::query()
                ->where('id', $service['service_id'])
                ->value('price');
            if ($price === null || $price === 0)
            {
                throw new Exception('Service not found or price is not set.');
            }

            $totalPricesOfServices = $price * $service['service_quantity'];

            return $totalPricesOfServices;
        }
        catch (Exception $e)
        {
            throw new Exception('Error calculating total price of services: ' . $e->getMessage());
        }

    }
}
