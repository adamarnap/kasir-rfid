<?php

namespace Database\Seeders;

use App\Enums\ParentRelationshipEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Reset cache Spatie Permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * List Permission sesuai permintaan
         */
        $permissions = [
            // Admin
            'dashboard.read', 'dashboard.create', 'dashboard.update', 'dashboard.delete',
            'transactions.read', 'transactions.create', 'transactions.update', 'transactions.delete',
            'active-transactions.read', 'active-transactions.create', 'active-transactions.update', 'active-transactions.delete',
            'rfid.read', 'rfid.create', 'rfid.update', 'rfid.delete',
            'topup.read', 'topup.create', 'topup.update', 'topup.delete',
            'master.read', 'master.create', 'master.update', 'master.delete',
            'categories.read', 'categories.create', 'categories.update', 'categories.delete',
            'products.read', 'products.create', 'products.update', 'products.delete',
            'students.create', 'students.read', 'students.update', 'students.delete',
            'parents.read', 'parents.update', 'parents.delete', 'parents.create',
            'report.read', 'report.create', 'report.update', 'report.delete',
            'report-transactions.read', 'report-transactions.create', 'report-transactions.update', 'report-transactions.delete',
            'report-topup.read', 'report-topup.create', 'report-topup.update', 'report-topup.delete',
            'profile.read', 'profile.create', 'profile.update', 'profile.delete',
            'settings.read', 'settings.create', 'settings.update', 'settings.delete',
            'users.read', 'users.create', 'users.update', 'users.delete',
            'roles.read', 'roles.create', 'roles.update', 'roles.delete',
            'navs.read', 'navs.create', 'navs.update', 'navs.delete',
            'preferences.read', 'preferences.create', 'preferences.update', 'preferences.delete',
        ];

        // Buat permission jika belum ada
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        /**
         * ✅ Buat Role dan Assign Permission
         */
        $roleDeveloper = Role::firstOrCreate(['name' => RoleEnum::DEVELOPER->value]);
        $roleAdmin = Role::firstOrCreate(['name' => RoleEnum::ADMIN->value]);
        $roleCashier = Role::firstOrCreate(['name' => RoleEnum::CASHIER->value]);
        $roleStudent = Role::firstOrCreate(['name' => RoleEnum::STUDENT->value]);
        $roleParent = Role::firstOrCreate(['name' => RoleEnum::PARENT->value]);

        /* Assign permission sesuai role */

        // SYNC PERMISSIONS FOR DEVELOPER ROLE
        $roleDeveloper->syncPermissions(Permission::all());

        // Admin => semua permission
        $adminPermissions = [
            // Admin
            'dashboard.read', 'dashboard.create', 'dashboard.update', 'dashboard.delete',
            'transactions.read', 'transactions.create', 'transactions.update', 'transactions.delete',
            'active-transactions.read', 'active-transactions.create', 'active-transactions.update', 'active-transactions.delete',
            'rfid.read', 'rfid.create', 'rfid.update', 'rfid.delete',
            'topup.read', 'topup.create', 'topup.update', 'topup.delete',
            'master.read', 'master.create', 'master.update', 'master.delete',
            'categories.read', 'categories.create', 'categories.update', 'categories.delete',
            'products.read', 'products.create', 'products.update', 'products.delete',
            'students.create', 'students.read', 'students.update', 'students.delete',
            'parents.read', 'parents.update', 'parents.delete', 'parents.create',
            'report.read', 'report.create', 'report.update', 'report.delete',
            'report-transactions.read', 'report-transactions.create', 'report-transactions.update', 'report-transactions.delete',
            'report-topup.read', 'report-topup.create', 'report-topup.update', 'report-topup.delete',
            'profile.read', 'profile.create', 'profile.update', 'profile.delete',
            'settings.read', 'settings.create', 'settings.update', 'settings.delete',
            'users.read', 'users.create', 'users.update', 'users.delete',
            'roles.read', 'roles.create', 'roles.update', 'roles.delete',
        ];
        // SYNC PERMISSIONS FOR ADMIN ROLE
        $roleAdmin->syncPermissions($adminPermissions);

        $cashierPermissions = [
            // Cashier
            'dashboard.read', 'dashboard.create', 'dashboard.update', 'dashboard.delete',
            'transactions.read', 'transactions.create', 'transactions.update', 'transactions.delete',
            'active-transactions.read', 'active-transactions.create', 'active-transactions.update', 'active-transactions.delete',
            'rfid.read', 'rfid.create', 'rfid.update', 'rfid.delete',
            'topup.read', 'topup.create', 'topup.update', 'topup.delete',
            'master.read', 'master.create', 'master.update', 'master.delete',
            'categories.read', 'categories.create', 'categories.update', 'categories.delete',
            'products.read', 'products.create', 'products.update', 'products.delete',
            'students.create', 'students.read', 'students.update', 'students.delete',
            'parents.read', 'parents.update', 'parents.delete', 'parents.create',
            'report.read', 'report.create', 'report.update', 'report.delete',
            'report-transactions.read', 'report-transactions.create', 'report-transactions.update', 'report-transactions.delete',
            'report-topup.read', 'report-topup.create', 'report-topup.update', 'report-topup.delete',
            'profile.read', 'profile.create', 'profile.update', 'profile.delete',
        ];

        // SYNC PERMISSIONS FOR CASHIER ROLE
        $roleCashier->syncPermissions($cashierPermissions);

        // Student => hanya permission Student & Parent
        $studentPermissions = [
            'dashboard.read',
            'report.read',
            'report-transactions.read',
            'report-topup.read',
            'profile.read',
            'profile.create',
            'profile.update',
            'profile.delete'
        ];
        // SYNC PERMISSIONS FOR STUDENT ROLE
        $roleStudent->syncPermissions($studentPermissions);

        // SYNC PERMISSIONS FOR PARENT ROLE
        $roleParent->syncPermissions($studentPermissions);

        /**
         * ✅ Buat User untuk setiap role
         */
        $usersData = [
            [
                'id' => 1,
                'name' => 'Developer Application',
                'email' => 'developerlaravelbase@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'role' => $roleDeveloper
            ],
            [
                'id' => 2,
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'role' => $roleAdmin
            ],
            [
                'id' => 3,
                'name' => 'Cashier User',
                'email' => 'cashier@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'role' => $roleCashier
            ],
            [
                'id' => 4,
                'name' => 'Student User',
                'email' => 'student@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'role' => $roleStudent,
                'student_account' => [
                    'student_id' => 4, 
                    'kelas' => '10A',
                    'balance' => 100.00,
                    'status' => 'active'
                ]
            ],
            [
                'id' => 5,
                'name' => 'Parent User',
                'email' => 'parent@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'role' => $roleParent,
                'parent_student' => [
                    'student_id' => 4,
                    'parent_id' => 5,
                    'relationship' => ParentRelationshipEnum::MOTHER->value
                ]
            ],
        ];

        // Loop through each user data and create or update the user
        foreach ($usersData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $data['password'],
                    'email_verified_at' => $data['email_verified_at'] ?? now(),
                ]
            );
            $user->assignRole($data['role']);

            // If the user has a student account, create it
            if (isset($data['student_account'])) {
                $user->studentAccount()->updateOrCreate(
                    ['student_id' => $data['student_account']['student_id']],
                    [
                        'kelas' => $data['student_account']['kelas'],
                        'balance' => $data['student_account']['balance'],
                        'status' => $data['student_account']['status']
                    ]
                );
            }
            // If the user has a parent_student relationship, create it
            if (isset($data['parent_student'])) {
                $user->parent()->updateOrCreate(
                    [
                        'student_id' => $data['parent_student']['student_id'], 
                        'parent_id' => $data['parent_student']['parent_id']
                    ],
                    [
                        'relationship' => $data['parent_student']['relationship']
                    ]
                );
            }
        }
    }
}
