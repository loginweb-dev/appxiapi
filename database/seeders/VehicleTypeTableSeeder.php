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
            'name' => "Motocicleta",
            'description' => '',
            'image' => '',
            'status' => 1
        ]);

        VehicleType::create([
            'name' => "Automóvil",
            'description' => '',
            'image' => '',
            'status' => 1
        ]);
    }
}
