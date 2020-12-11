<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\LoginWebController as LoginWeb;

use App\Models\Location;

class LocationsController extends Controller
{
    // Obtener la ciudad más cercana según ubicación
    public function proximal_location($lat, $lon){
        $locations = Location::where('status', 1)->where('deleted_at', NULL)->get();
        
        if(count($locations)){
            // Poner la primera ubicacion como la mas cercana para comprar
            $location_id = $locations[0]->id;
            $distancia_minima = (new Loginweb)->distanciaEnKm($locations[0]->latitude, $locations[0]->longitude, $lat, $lon);;

            foreach ($locations as $item) {
                $distancia = (new Loginweb)->distanciaEnKm($item->latitude, $item->longitude, $lat, $lon);
                if($distancia_minima > $distancia){
                    $distancia_minima = $distancia;
                    $location_id = $item->id;
                }
            }
            return ["location_id" => $location_id, "distance" => $distancia_minima];
        }else{
            return null;
        }
    }
}
