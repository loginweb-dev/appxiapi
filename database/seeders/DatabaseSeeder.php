<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class DatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';
    
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->seed('VoyagerDatabaseSeeder');
        $this->call([
            UserTableSeeder::class,
            LocationTableSeeder::class,
            PaymentTypeTableSeeder::class,
            VehicleTypeTableSeeder::class
        ]);
    }
}
