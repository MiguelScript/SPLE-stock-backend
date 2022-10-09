<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vendedores')->insert([
            [
                'codigo' => 'VEN1',
                'nombre' => 'Miguel',
                'apellido' => 'Acosta',
                'status' => 1
            ],
            [
                'codigo' => 'VEN2',
                'nombre' => 'Karelys',
                'apellido' => 'Acosta',
                'status' => 1            
            ]
        ]);
    }
}
