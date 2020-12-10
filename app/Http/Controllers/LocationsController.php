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
            $distancia_minima = (new Loginweb)->distanciaEnKm($locations[0]->latitud, $locations[0]->longitud, $lat, $lon);;

            foreach ($locations as $item) {
                $distancia = (new Loginweb)->distanciaEnKm($item->latitud, $item->longitud, $lat, $lon);
                if($distancia_minima > $distancia){
                    $distancia_minima = $distancia;
                    $location_id = $item->id;
                }
            }
            return $location_id;
        }else{
            return null;
        }
    }
}
