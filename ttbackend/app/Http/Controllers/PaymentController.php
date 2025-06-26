<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payments = $this->paymentRepository->getPayments($request->all());

        return response()->json([
            'success' => true,
            'message' => $payments->isEmpty() ? 'No payments found.' : 'Payments fetched successfully.',
            'payments' => $payments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        $validatedData = $request->validated();
        $this->paymentRepository->createPayment($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentRequest $request, $id)
    {
        $validatedData = $request->validated();
        $updatedPayment = $this->paymentRepository->updatePayment($id, $validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully.',
            'payment' => $updatedPayment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->paymentRepository->deletePayment($id);

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully.',
        ], 200);
    }
}
