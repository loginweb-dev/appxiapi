<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Models
use App\Models\PaymentType;

class PaymentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        PaymentType::create([
            'name' => "Efectivo",
            'description' => 'Dinero en efectivo',
            'image' => '',
            'status' => 1
        ]);

        PaymentType::create([
            'name' => "Tarjeta",
            'description' => 'Pago con tarjeta de crédito',
            'image' => '',
            'status' => 1
        ]);

        PaymentType::create([
            'name' => "Transferencia",
            'description' => 'Tranferencia bancaria',
            'image' => '',
            'status' => 1
        ]);
    }
}
