<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_id',
        'appointment_date',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'notes'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services')
                    ->withPivot(['service_quantity', 'total_price_at_appointment'])
                    ->withTimestamps();
    }
}
