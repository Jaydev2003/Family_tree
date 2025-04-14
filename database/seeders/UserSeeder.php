<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert one user with hashed password
      

        User::create([
            'name' => 'Admin User',
            'email' => 'admin123@gmail.com',
            'password' => Hash::make('123456'), // Hashing password with Bcrypt
        ]);

      
    }
}
