<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user if doesn't exist
        $adminUser = User::where('email', 'admin@test.com')->first();
        
        if (!$adminUser) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            
            echo "Admin user created successfully!\n";
            echo "Email: admin@test.com\n";
            echo "Password: password\n";
        } else {
            echo "Admin user already exists!\n";
        }
    }
}