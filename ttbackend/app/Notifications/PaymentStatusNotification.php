<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentStatusNotification extends Notification
{
    use Queueable;
    protected $payment;
    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $mail = (new MailMessage)
            ->subject('Payment Status')
            ->greeting('Hello ' . $this->payment->appointment->customer->name . '!')
            ->line('📅 Date of Payment: ' . $this->payment->date)
            ->line('💰 Amount: ' . '₱' . $this->payment->amount)
            ->line('Your current payment status is ' . $this->payment->status)
            ->line('Thank you for your payment!');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
