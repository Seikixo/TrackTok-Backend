<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Notifications\PaymentCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPaymentCompletedEmail implements ShouldQueue
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
        $this->payment->appointment->customer->notify(new PaymentCompleted($this->payment));
    }
}
