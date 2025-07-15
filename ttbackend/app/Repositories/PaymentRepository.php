<?php

namespace App\Repositories;

use App\Jobs\SendPaymentStatusEmailJob;
use App\Models\Payment;
use App\Services\PaymentService;

class PaymentRepository
{

    public function __construct(
        private PaymentService $paymentService
    ){}

    public function getPayments(array $params = [])
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

        if (!empty($params['per_page'])) {
            return $query->paginate($params['per_page'] ?? 10);
        }

        return $query->get();
    }

    public function createPayment(array $data)
    {
        $appointment_id = $data['appointment_id'];
        $amount = $data['amount'];

        $status = $this->paymentService->categorizePayment($appointment_id, $amount);
        $data['status'] = $status;

        $payment = Payment::create($data);
        SendPaymentStatusEmailJob::dispatch($payment);
        return $payment;
    }

    public function updatePayment($id, array $data)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($data);

        return $payment;
    }

    public function deletePayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return $payment;
    }
}
