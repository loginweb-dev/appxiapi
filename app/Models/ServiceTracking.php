<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTracking extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'status', 'observations'];

}
