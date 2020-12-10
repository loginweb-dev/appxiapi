<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['customer_location_id', 'suggested_amount', 'amount_paid', 'discount_amount', 'driver_id', 'payment_type_id', 'rating', 'observations', 'status'];
}
