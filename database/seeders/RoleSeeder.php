<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Import Role model
use Spatie\Permission\Models\Permission; // Import Permission model (optional for future use)

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles if they don't exist
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Editor']);

        // Permissions can be added here later if needed
        // Example: Permission::create(['name' => 'edit articles']);
        // $role = Role::findByName('Editor');
        // $role->givePermissionTo('edit articles');
    }
}
