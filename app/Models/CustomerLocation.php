<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLocation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'customer_id', 'location_id', 'latitude', 'longitude', 'favorite', 'stored'];
}
