<?php

/**
 * @author Yayong Ditya <https://gitlab.com/yayong.dk>
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Navigation as ModelsNavigation;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        // create permissions for navigation
        $navSlug = ModelsNavigation::pluck('slug')->toArray();
        $this->generatePermissions($navSlug);
        $permissions = Permission::all();

        // create roles and assign existing permissions
        $developer = Role::create(['name' => 'developer']);
        $developer->syncPermissions($permissions);

        // create admin users
        $user = \App\Models\User::factory()->create([
            'name' => 'Laravel Base Developer',
            'email' => 'developerlaravelbase@gmail.com',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($developer);
    }
    
    /**
     * Fungsi untuk menghasilkan permission berdasarkan slug navigasi
    */
    public function generatePermissions($permissions)
    {
        $permissionsList = [];
        foreach ($permissions as $permission) {
            $permissionsList[] = ['name' => $permission . '.read', 'guard_name' => 'web'];
            $permissionsList[] = ['name' => $permission . '.create', 'guard_name' => 'web'];
            $permissionsList[] = ['name' => $permission . '.update', 'guard_name' => 'web'];
            $permissionsList[] = ['name' => $permission . '.delete', 'guard_name' => 'web'];
        }
        return Permission::insert($permissionsList);
    }
}
