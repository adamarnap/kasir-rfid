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
    // The structure of each navigation item is as follows:
    /* [
        'id' => null,
        'name' => '',
        'url' => '',
        'slug' => '',
        'icon' => '',
        'order' => 1,
        'parent_id' => null,
        'active' => true,
        'display' => true,
    ], */
    public function run(): void
    {
        $navigations = [
            /* Dashboard */
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
            /* Transaksi */
            [
                'id' => 2,
                'name' => 'Transaksi',
                'url' => 'transactions.index',
                'slug' => 'transactions',
                'icon' => 'bi bi-coin',
                'order' => 2,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            /* Transaksi Aktif */
            [
                'id' => 13,
                'name' => 'Transaksi Aktif',
                'url' => 'active-transactions.index',
                'slug' => 'active-transactions',
                'icon' => 'bi bi-cart4',
                'order' => 3,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            /* Kartu RFID */
            [
                'id' => 3,
                'name' => 'Kartu RFID',
                'url' => 'rfid.index',
                'slug' => 'rfid',
                'icon' => 'bi bi-credit-card',
                'order' => 4,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            /* Top Up Saldo */
            [
                'id' => 4,
                'name' => 'Top Up Saldo',
                'url' => 'topup.index',
                'slug' => 'topup',
                'icon' => 'bi bi-wallet2',
                'order' => 5,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            /* Master Data */
            [
                'id' => 5,
                'name' => 'Master Data',
                'url' => '#',
                'slug' => 'master',
                'icon' => 'bi bi-database',
                'order' => 6,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 6,
                'name' => 'Kategori Produk',
                'url' => 'master.categories.index',
                'slug' => 'categories',
                'icon' => 'bi bi-tags',
                'order' => 1,
                'parent_id' => 5,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 7,
                'name' => 'Produk',
                'url' => 'master.products.index',
                'slug' => 'products',
                'icon' => 'bi bi-box-seam',
                'order' =>  2,
                'parent_id' => 5,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 8,
                'name' => 'Siswa',
                'url' => 'master.students.index',
                'slug' => 'students',
                'icon' => 'bi bi-person-badge',
                'order' => 3,
                'parent_id' => 5,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 9,
                'name' => 'Wali Siswa',
                'url' => 'master.parents.index',
                'slug' => 'parents',
                'icon' => 'bi bi-person-hearts',
                'order' => 4,
                'parent_id' => 5,
                'active' => true,
                'display' => true,
            ],
            
            /* Laporan */
            [
                'id' => 10,
                'name' => 'Laporan',
                'url' => '#',
                'slug' => 'report',
                'icon' => 'bi bi-file-earmark-text',
                'order' =>  7,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 11,
                'name' => 'Riwayat Transaksi',
                'url' => 'report.transactions.index',
                'slug' => 'report-transactions',
                'icon' => 'bi bi-clock-history',
                'order' => 1,
                'parent_id' => 10,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 12,
                'name' => 'Riwayat Top Up',
                'url' => 'report.topup.index',
                'slug' => 'report-topup',
                'icon' => 'bi bi-arrow-clockwise',
                'order' => 2,
                'parent_id' => 10,
                'active' => true,
                'display' => true,
            ],

            /* Settings */
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
