<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Import User model
use Spatie\Permission\Models\Role; // Import Role model
use Illuminate\Support\Facades\Hash; // Import Hash facade

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create the Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Create the admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'hsshin@epart.com'], // Find by email
            [
                'name' => 'Admin User', // Default name
                'password' => Hash::make('dlvkxm1!'), // Use the provided password
                'email_verified_at' => now(), // Mark email as verified
            ]
        );

        // Assign the Admin role to the user
        $adminUser->assignRole($adminRole);
    }
}
