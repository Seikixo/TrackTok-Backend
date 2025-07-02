<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Notifications\PaymentStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPaymentStatusEmailJob implements ShouldQueue
{
    use Queueable;

    protected $payment;
    /**
     * Create a new job instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $loadPayment = $this->payment->load('appointment.customer');
        $this->payment->appointment->customer->notify(new PaymentStatusNotification($loadPayment));
    }
}
