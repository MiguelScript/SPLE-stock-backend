<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'miguel',
                'last_name' => 'acosta',
                'email' => 'acostalugo.m@gmail.com',
                'password' => Hash::make('1234567890'),
                'role_id' => 1,
            ],
            [
                'name' => 'angel',
                'last_name' => 'acosta',
                'email' => 'cliente.miguel@gmail.com',
                'password' => Hash::make('1234567890'),
                'role_id' => 1,
            ],
            [
                'name' => 'usuario',
                'last_name' => 'miguel',
                'email' => 'usuario.miguel@gmail.com',
                'password' => Hash::make('1234567890'),
                'role_id' => 1,
            ],
        ]);
    }
}
