<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmedNotification extends Notification
{
    use Queueable;
    protected $appointment;
    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
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
            ->subject('Appointment Confirmation')
            ->greeting('Greetings!' . $this->appointment->customer->name . '!')
            ->line('We are confirming your appointment, here are the details:')
            ->line('📅 Date: ' . $this->appointment->appointment_date)
            ->line('🕒 Time: ' . $this->appointment->start_time . ' to ' . $this->appointment->end_time)
            ->line('🧾 Services:');
            foreach ($this->appointment->services as $service) {
                $mail->line('- ' . $service->name . ' (Qty: ' . $service->pivot->service_quantity . ', ₱' . number_format($service->pivot->total_price_of_services, 2) . ')');
            }

            $mail->line('💰 Total Price: ₱' . number_format($this->appointment->total_price, 2))
                ->action('View Appointment', url('/appointments/' . $this->appointment->id))
                ->line('Thank you!');

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
