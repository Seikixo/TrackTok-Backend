<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Payment;
use Exception;

class PaymentService
{

    public function categorizePayment($appointment_id, $amount)
    {

        try
        {
            $appointment = Appointment::query()
                ->with('payments')
                ->findOrFail($appointment_id);
            
            $payments = $appointment->payments
                ->sum('amount');

            $totalPayment = $payments + $amount;

            $appointmentTotalPrice = $appointment->total_price;

            if($totalPayment > $appointmentTotalPrice)
            {
                return "Overpaid";
            }
            elseif($totalPayment < $appointmentTotalPrice)
            {
                return "Pending";
            }
            elseif($totalPayment == $appointmentTotalPrice)
            {
                return "Completed";
            }
            else
            {
                return "Failed";
            }
        }
        catch (Exception $e)
        {
            throw new Exception('Error in categorizing Payment', $e->getMessage());
        }
        
    }
}