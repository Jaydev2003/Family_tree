<?php

namespace Database\Seeders;

use App\Models\data;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class dataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        data::create([
            'name' => 'Rajabhai & maniben',
            'email' => 'rajabhai@gmail.com',
            'address' => 'vastdi',
            'phone' => '9658426518',
            'status' => 'married',
            'relation' => 'father',
            'gender' => 'male',
            

        ]);
    }
}
