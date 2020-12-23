<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'user_id', 'vehicle_id'];

    public function vehicle(){
        return $this->belongsTo('App\Models\Vehicle', 'vehicle_id');
    }
}
