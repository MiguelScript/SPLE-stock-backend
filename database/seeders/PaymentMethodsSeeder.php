<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('metodos_pago')->insert([
            [
                'nombre' => 'Pago móvil',
                'status' => 1
            ],
            [
                'nombre' => 'Efectivo',
                'status' => 1
            ]
        ]);
    }
}
