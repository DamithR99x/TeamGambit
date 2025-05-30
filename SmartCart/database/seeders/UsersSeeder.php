<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@smartcart.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        $admin->assignRole('admin');
        
        // Create profile for admin
        UserProfile::create([
            'user_id' => $admin->id,
            'phone' => '1234567890',
            'address_line_1' => '123 Admin Street',
            'city' => 'Admin City',
            'state' => 'State',
            'postal_code' => '12345',
            'country' => 'Country',
        ]);
        
        // Create customer user
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
        
        $customer->assignRole('customer');
        
        // Create profile for customer
        UserProfile::create([
            'user_id' => $customer->id,
            'phone' => '0987654321',
            'address_line_1' => '456 Customer Avenue',
            'city' => 'Customer City',
            'state' => 'State',
            'postal_code' => '54321',
            'country' => 'Country',
        ]);
    }
}
