<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLocation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'service_id', 'location_id', 'latitude', 'longitude', 'favorite', 'stored'];

    public function location(){
        return $this->belongsTo(Location::class, 'location_id');
    }
}
