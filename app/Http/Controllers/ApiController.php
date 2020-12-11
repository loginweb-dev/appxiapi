<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\LocationsController;

// Models
use App\Models\User;
use App\Models\Customer;
use App\Models\ServiceLocation;
use App\Models\Service;
use App\Models\PaymentType;
use App\Models\VehicleType;

class ApiController extends Controller
{
    public function external_service_init(Request $request){
        
        DB::beginTransaction();
        try {
            if($request->client_phone){
                $phone = substr($request->client_phone, -8);
                $user = User::where('phone', $phone)->first();
                if($user){
                    $customer = Customer::where('user_id', $user->id)->first();
                }else{
                    $user = User::create([
                        'role_id' => 2,
                        'name' => $request->client_name['name'],
                        'email' => Str::random(15).'@appxi.com',
                        'password' => Hash::make(Str::random(10)),
                        'phone' => $phone,
                    ]);
    
                    $customer = Customer::create([
                        'first_name' => $request->client_name['name'],
                        'user_id' => $user->id
                    ]);
    
                }
    
                $service = Service::create([
                    'customer_id' => $customer->id,
                    'status' => 1
                ]);
    
                DB::commit();

                return url('api/external/service/map/'.$service->id);
    
            }else{
                return 'Invalid phone number';
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return 'Server error';
        }
    }

    public function external_service_map($id){
        $vehicle_types = VehicleType::where('status', 1)->where('deleted_at', NULL)->get();
        $payment_types = PaymentType::where('status', 1)->where('deleted_at', NULL)->get();
        return view('external_app.map', compact('id', 'vehicle_types', 'payment_types'));
    }

    public function external_service_store(Request $request){
        
        DB::beginTransaction();
        try {

            $service = Service::findOrFail($request->id);
            $service->latitude = $request->origin_latitude;
            $service->longitude = $request->origin_longitude;
            $service->payment_type_id = $request->payment_type_id;
            $service->vehicle_type_id = $request->vehicle_type_id;
            $service->save();
            
            // Obtener la localidad más próxima
            $location = (new LocationsController)->proximal_location($request->destiny_latitude, $request->destiny_longitude);
            
            if(!$location){
                return response()->json(['error' => 'Locations not availables']);
            }

            $service_location = ServiceLocation::create([
                'service_id' => $service->id,
                'location_id' => $location['location_id'],
                'latitude' => $request->destiny_latitude,
                'longitude' => $request->destiny_longitude
            ]);

            DB::commit();

            return response()->json(['data' => $service]);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 'Server error']);
        }
    }
}
