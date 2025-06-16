<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'duration',
        'price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services')
                    ->withPivot(['service_quantity', 'total_price_of_services'])
                    ->withTimestamps();

    }
}
