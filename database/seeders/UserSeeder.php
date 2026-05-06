<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'nomComplet' => 'Abdou Karim Ndiaye',
             'email' => 'karim@gmail.com',
             'phone' => '787776655',
             'role' => 'ADMIN',
            'password' => Hash::make('admin123')
         ]);
    }
}
