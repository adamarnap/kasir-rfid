<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $navigations = [
            [
                'id' => 1,
                'name' => 'Dashboard',
                'url' => 'dashboard',
                'slug' => 'dashboard',
                'icon' => 'bi bi-house',
                'order' => 1,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 100,
                'name' => 'Profil Saya',
                'url' => 'profile.edit',
                'slug' => 'profile',
                'icon' => 'bi bi-person-bounding-box',
                'order' => 100,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 101,
                'name' => 'Pengaturan',
                'url' => '#',
                'slug' => 'settings',
                'icon' => 'bi bi-sliders',
                'order' => 101,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 102,
                'name' => 'Pengguna',
                'url' => 'users.index',
                'slug' => 'users',
                'icon' => '', // Assuming no icon specified
                'order' => 1,
                'parent_id' => 101, // Nested under Settings
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 103,
                'name' => 'Peran',
                'url' => 'roles.index',
                'slug' => 'roles',
                'icon' => '', // Assuming no icon specified
                'order' => 2,
                'parent_id' => 101, // Nested under Settings
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 104,
                'name' => 'Menu',
                'url' => 'navs.index',
                'slug' => 'navs',
                'icon' => '', // Assuming no icon specified
                'order' => 3,
                'parent_id' => 101, // Nested under Settings
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 105,
                'name' => 'Preferensi',
                'url' => 'preferences.index',
                'slug' => 'preferences',
                'icon' => '', // Assuming no icon specified
                'order' => 4,
                'parent_id' => 101, // Nested under Settings
                'active' => true,
                'display' => true,
            ],
            
        ];
        // Insert data into the 'navigations' table
        DB::table('navigations')->insert($navigations);
    }
}
