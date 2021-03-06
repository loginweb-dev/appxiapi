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
use App\Models\Driver;
use App\Models\ServiceLocation;
use App\Models\Service;
use App\Models\ServiceOffer;
use App\Models\PaymentType;
use App\Models\VehicleType;
use App\Models\Vehicle;
use App\Models\Notification;
use App\Models\VehicleTranking;

class ApiController extends Controller
{
    // =========== Auth ===========
    public function login(Request $request){
        $user = null;
        $token = null;

        // return 1;

        if($request->social_login){
            $user = User::with('driver.vehicle')->where('email', $request->email)->first() ?? $this->new_driver($request);
            $token = $user->createToken('appxiapi')->accessToken;

            // Actualizar token de firebase
            if($request->firebase_token){
                User::where('id', $user->id)->update([
                    'firebase_token' => $request->firebase_token
                ]);
            }
        }else{
            $credentials = ['email' => $request->email, 'password' => $request->password];
            if (Auth::attempt($credentials)) {
                $auth = Auth::user();
                $token = $auth->createToken('livemedic')->accessToken;
                $user = User::with('driver.vehicle')->where('id', $auth->id)->first();

                // Actualizar token de firebase
                if($request->firebase_token){
                    $user_update = User::find($user->id);
                    $user_update->firebase_token = $request->firebase_token;
                    $user_update->save();
                }
            }
        }

        if($user && $token){
            return response()->json(['user' => $user, 'token' => $token]);
        }else{
            return response()->json(['error' => "credentials don't exist"]);
        }
    }

    public function register(Request $request){
        
            $user = $this->new_driver($request);
            if(!$user){
                return response()->json(['error' => "email exist"]);
            }
            $token = $user->createToken('livemedic')->accessToken;
            
            if($user && $token){
                return response()->json(['user' => $user, 'token' => $token]);
            }else{
                return response()->json(['error' => "registration failed"]);
            }
        
    }

    // =========== Vehicle types ===========
    public function vehicle_types_list(){
        $vehicle_types = VehicleType::where('deleted_at', NULL)->where('status', 1)->get();
        return response()->json(['vehicle_types' => $vehicle_types]);
    }

    // =========== Vehicles ===========
    public function vehicle_tracking(Request $request){
        DB::beginTransaction();
        try {
            Vehicle::where('id', $request->vehicle_id)->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
            VehicleTranking::create([
                'vehicle_id' => $request->vehicle_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
            DB::commit();

            event(new \App\Events\TrackingDriverEvent($request->vehicle_id, Vehicle::find($request->vehicle_id)));

            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 1]);
        }
    }

    // =========== Services ===========
    public function services_list($driver_id){
        $services = Service::with(['driver', 'customer', 'service_location'])
                    ->where('driver_id', $driver_id)->get();
        $services_offer = Service::with(['driver', 'customer', 'service_location'])
                                    ->whereHas('offer', function($q) use ($driver_id){
                                        $q->where('driver_id', $driver_id)->where('status', 1);
                                    })->get();
        return response()->json(['services' => $services, 'servicesOffer' => $services_offer]);
    }

    public function services_notifications_list($user_id){
        $services = $this->get_service_notifications($user_id);
        return response()->json(['services' => $services]);
    }

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
                    'status' => 1,
                    'details' => '{"id": "'.$request->service_id.'"}'
                ]);
    
                DB::commit();

                return url('map/'.$service->id);
    
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

            // Obtener la localidad más próxima
            $location = (new LocationsController)->proximal_location($request->destiny_latitude, $request->destiny_longitude);
            
            if(!$location){
                return response()->json(['error' => 'Locations not availables']);
            }
            $service_location = ServiceLocation::create([
                'location_id' => $location['location_id'],
                'latitude' => $request->destiny_latitude,
                'longitude' => $request->destiny_longitude
            ]);
            $service_location->latitude = $request->destiny_latitude;
            $service_location->longitude = $request->destiny_longitude;
            $service_location->save();

            $service = Service::findOrFail($request->id);
            $service->latitude = $request->origin_latitude;
            $service->longitude = $request->origin_longitude;
            $service->service_location_id = $service_location->id;
            $service->payment_type_id = $request->payment_type_id;
            $service->vehicle_type_id = $request->vehicle_type_id;
            $service->save();

            DB::commit();

            $this->send_notification_drivers($service->id);

            return response()->json(['data' => $service]);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 'Server error']);
        }
    }

    public function offer_service(Request $request){
        try {
            ServiceOffer::create([
                'service_id' => $request->service_id,
                'driver_id' => $request->driver_id,
                'amount' => $request->amount
            ]);

            // Get updates services
            $services = Service::with(['driver', 'customer', 'service_location'])->where('driver_id', $request->driver_id)->get();

            // Get updates notifications
            $notifications = $this->get_service_notifications($request->user_id);

            DB::commit();
            return response()->json(['services' => $services, 'notifications' => $notifications]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
        }
    }

    public function accept_service(Request $request){
        DB::beginTransaction();
        try {
            Service::where('id', $request->service_id)->update([
                'driver_id' => $request->driver_id,
                'status' => 2,
            ]);

            Notification::where('details', '{"id": '.$request->service_id.'}')->where('user_id', $request->user_id)->update([
                'status' => 2,
            ]);

            // Get updates services
            $services = Service::with(['driver', 'customer', 'service_location'])->where('driver_id', $request->driver_id)->get();

            DB::commit();
            return response()->json(['services' => $services]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 'Server error']);
        }
    }

    public function update_service(Request $request){
        DB::beginTransaction();
        try {
            Service::where('id', $request->service_id)->update([
                'driver_id' => $request->driver_id,
                'status' => $request->status,
            ]);

            // Get updates services
            $services = Service::with(['driver', 'customer', 'service_location'])->where('driver_id', $request->driver_id)->get();

            DB::commit();
            return response()->json(['services' => $services]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 'Server error']);
        }
    }

    // ======================================

    public function new_driver($data){
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data->first_name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
                'avatar' => $data->avatar,
                'firebase_token' => $data->firebase_token
            ]);

            $vehicle = Vehicle::create([
                'vehicle_type_id' => $data->vehicle_type_id ?? 1
            ]);

            $driver = Driver::create([
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'phones' => $data->phones,
                'address' => $data->address,
                'user_id' => $user->id,
                'vehicle_id' => $vehicle ? $vehicle->id : null
            ]);

            DB::commit();

            return User::with('driver')->where('id', $user->id)->first();

        } catch (\Throwable $th) {
            DB::rollback();
            return null;
        }
    }

    public function send_notification_drivers($id){
        $url =  env('FIREBASE_CLOUD_MESSAGING_URL');
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'key='.env('FIREBASE_CLOUD_MESSAGING_TOKEN')
        ];

        try {
            $users = User::where('firebase_token', '<>', NULL)->get();
            foreach ($users as $user) {
                $body = [
                    "to" => $user->firebase_token,
                    "content_available" => true,
                    "notification" => [
                        "title" => 'Nueva solicitud de taxi',
                        "body" => 'Un cliente cercano a ti solicitó taxi.',
                        "priority" => "high"
                    ],
                    "data" => [
                        "type" => "service"
                    ]
                ];
        
                $this->send_request('post', $url, $headers, $body);

                // Create notification
                Notification::create([
                    'type' => 'service',
                    'user_id' => $user->id,
                    'details' => '{"id": '.$id.'}',
                ]);
            }
        } catch (\Throwable $th) {}
    }

    public function get_service_notifications($user_id){
        try {
            $notifications = Notification::where('type', 'service')
                            ->where('user_id', $user_id)
                            ->where('status', 1)->get();
            $array = [];
            foreach ($notifications as $value) {
                $detail = json_decode($value->details);
                array_push($array, $detail->id);
            }
            return Service::with(['driver', 'customer', 'service_location'])
                        ->whereIn('id', $array)
                        ->get();
        } catch (\Throwable $th) {
            return [];
        }       
    }
}
