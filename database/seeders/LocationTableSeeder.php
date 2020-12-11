<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Modelas
use App\Models\Location;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Location::create([
            "title" => "Santísima Trinidad - Beni",
            "latitude" => "-14.8358334",
            "longitude" => "-64.9085435",
            "status" => 1
        ]);
        Location::create([
            "title" => "Guayaramerín - Beni",
            "latitude" => "-10.821868",
            "longitude" => "-65.357333",
            "status" => 1
        ]);
        Location::create([
            "title" => "Riberalta - Beni",
            "latitude" => "-11.002372",
            "longitude" => "-66.052043",
            "status" => 1
        ]);
    }
}
