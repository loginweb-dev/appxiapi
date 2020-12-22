<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'driver_id', 'payment_type_id', 'vehicle_type_id', 'latitude', 'longitude', 'service_location_id', 'suggested_amount', 'amount_paid', 'discount_amount', 'rating', 'observations', 'status', 'platform'];

    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function driver(){
        return $this->belongsTo('App\Models\Driver', 'driver_id');
    }

    public function service_location(){
        return $this->belongsTo('App\Models\ServiceLocation', 'service_location_id');
    }
}
