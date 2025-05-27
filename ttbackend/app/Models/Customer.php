<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'contact_number',
        'email',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
