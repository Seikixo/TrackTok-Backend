<?php

namespace App\Repositories;

use App\Jobs\SendPaymentStatusEmailJob;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;

class PaymentRepository
{

    public function __construct(
        private PaymentService $paymentService
    ){}

    public function getPayments(array $params = [])
    {
        $hasQueryModifiers = !empty($params['search']) 
            || !empty($params['sort_by']) 
            || !empty($params['sort_order']) 
            || !empty($params['date']) 
            || !empty($params['status']) 
            || !empty($params['per_page']);

        if($hasQueryModifiers)
        {
            $query = Payment::query()
                ->with('appointment.customer');

            if (!empty($params['search'])) {
                $query->whereHas('appointment.customer', function ($q) use ($params) {
                    $q->where('name', 'like', '%' . $params['search'] . '%');
                });
            }

            if (!empty($params['date'])) {
                $query->whereDate('date', $params['date']);
            }

            if (!empty($params['status'])) {
                $query->where('status', $params['status']);
            }

            $sortable = ['date', 'amount', 'status', 'created_at'];
            if (!empty($params['sort_by']) && in_array($params['sort_by'], $sortable)) {
                $order = in_array($params['sort_order'] ?? 'asc', ['asc', 'desc']) ? $params['sort_order'] : 'asc';
                $query->orderBy($params['sort_by'], $order);
            }

            return !empty($params['per_page'])
                ? $query->paginate($params['per_page'] ?? 10)
                : $query->get();
        }


        return Cache::remember('all_payments', 60, function () {
            return Payment::with('appointment.customer')->get();
        });
    }

    public function createPayment(array $data)
    {
        $appointment_id = $data['appointment_id'];
        $amount = $data['amount'];

        $status = $this->paymentService->categorizePayment($appointment_id, $amount);
        $data['status'] = $status;

        $payment = Payment::create($data);
        SendPaymentStatusEmailJob::dispatch($payment);
        Cache::forget('all_payments');
        return $payment;
    }

    public function updatePayment($id, array $data)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($data);
        Cache::forget('all_payments');
        return $payment;
    }

    public function deletePayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        Cache::forget('all_payments');
        return $payment;
    }
}
