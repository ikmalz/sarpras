<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admin = User::firstOrCreate([
            'email' => 'admin22@example.com',
        ], [
            'name' => 'Admin22',
            'password' => Hash::make('admin123'),
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $permissions = [
            'view items',
            'create items',
            'edit items',
            'delete items',
            'view category',
            'create category',
            'edit category',
            'delete category',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'approve borrowing',
            'approve return',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole->syncPermissions(Permission::all());
        $admin->assignRole($adminRole);
    }
}
