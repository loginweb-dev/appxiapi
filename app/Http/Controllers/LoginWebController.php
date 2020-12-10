<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginWebController extends Controller
{
    public function distanciaEnKm($point1_lat, $point1_long, $point2_lat, $point2_long) {
        // Cálculo de la distancia en grados
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
        $distance = $degrees * 111.13384;
        return round($distance, 2);
    }
}
