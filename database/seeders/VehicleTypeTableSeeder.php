<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Models
use App\Models\VehicleType;

class VehicleTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        VehicleType::create([
            'name' => "2 ruedas",
            'description' => '',
            'image' => '',
            'status' => 1
        ]);

        VehicleType::create([
            'name' => "4 ruedas",
            'description' => '',
            'image' => '',
            'status' => 1
        ]);
    }
}
