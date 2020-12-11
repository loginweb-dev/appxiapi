<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'driver_id', 'payment_type_id', 'vehicle_type_id', 'latitude', 'longitude', 'suggested_amount', 'amount_paid', 'discount_amount', 'rating', 'observations', 'status', 'platform'];

}
