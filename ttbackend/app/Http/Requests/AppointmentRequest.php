<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:Scheduled,Confirmed,Completed,Cancelled',
            'notes' => 'nullable|string|max:1000',

            'services' => 'nullable|array',
            'services.*.service_id' => 'required_with:services|exists:services,id',
            'services.*.service_quantity' => 'required_with:services|integer|min:1',
        ];
    }
}
