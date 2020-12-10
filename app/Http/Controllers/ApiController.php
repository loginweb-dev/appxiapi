<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\LocationsController;

// Models
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerLocation;
use App\Models\Service;

class ApiController extends Controller
{
    public function external_service(Request $request){
        if($request->phone){
            $phone = substr($request->phone, -8);
            $user = User::where('phone', $phone)->first();
            if($user){
                $customer = Customer::where('user_id', $user->id)->first();
            }else{
                $user = User::create([
                    'role_id' => 2,
                    'name' => $request->name,
                    'email' => Str::random(15).'@appxi.com',
                    'password' => Hash::make(Str::random(10)),
                    'phone' => $phone,
                ]);

                $customer = Customer::create([
                    'first_name' => $request->name,
                    'user_id' => $user->id
                ]);

            }

            $customer_location = CustomerLocation::create([
                'customer_id' => $customer->id,
                'location_id' => 1,
                'latitude' => '-14.8787887',
                'longitude' => '-64.767676'
            ]);

            $service = Service::create([
                'customer_location_id' => $customer_location->id,
                'payment_type_id' => 1,
                'status' => 1
            ]);

            return response()->json(['service' => $service]);

        }else{
            return response()->json(['error' => 'Invalid phone number']);
        }
    }
}
