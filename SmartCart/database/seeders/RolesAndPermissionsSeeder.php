<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for products
        $productPermissions = [
            'view products',
            'create products',
            'edit products',
            'delete products',
        ];

        // Create permissions for categories
        $categoryPermissions = [
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
        ];

        // Create permissions for orders
        $orderPermissions = [
            'view orders',
            'update orders',
            'delete orders',
        ];

        // Create permissions for customers
        $customerPermissions = [
            'view customers',
            'edit customers',
        ];

        // Create permissions for settings
        $settingPermissions = [
            'manage settings',
        ];

        // Create permissions for reports
        $reportPermissions = [
            'view reports',
        ];

        // Combine all permissions
        $allPermissions = array_merge(
            $productPermissions,
            $categoryPermissions,
            $orderPermissions,
            $customerPermissions,
            $settingPermissions,
            $reportPermissions
        );

        // Create each permission
        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create admin role and give it all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo($allPermissions);

        // Create customer role
        $customerRole = Role::create(['name' => 'customer']);
        $customerRole->givePermissionTo(['view products']);
    }
}
