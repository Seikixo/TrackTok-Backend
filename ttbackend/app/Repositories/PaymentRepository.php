<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
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
        return Payment::create($data);
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
