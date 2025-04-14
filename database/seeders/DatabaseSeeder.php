<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder; // Import the UserSeeder

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Call the UserSeeder
        $this->call(UserSeeder::class);
        $this->call(dataSeeder::class);
    }
}
