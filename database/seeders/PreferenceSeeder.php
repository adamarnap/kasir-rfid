<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'group' => 'site',
                'name' => 'app_name',
                'is_asset' => false,
                'value' => 'Adam Laravel',
            ],
            [
                'group' => 'site',
                'name' => 'title',
                'is_asset' => false,
                'value' => 'Adam Laravel',
            ],
            [
                'group' => 'site',
                'name' => 'copyright',
                'is_asset' => false,
                'value' => '&copy; Copyright <strong><span>Our Porject</span></strong>. All Rights Reserved',
            ],
            [
                'group' => 'site',
                'name' => 'credits',
                'is_asset' => false,
                'value' => 'Designed by <a href="https://my_project.com/">Our Projcet</a>',
            ],
            [
                'group' => 'site',
                'name' => 'logo',
                'is_asset' => true,
                'value' => 'assets/logo/logo.png',
            ],
            [
                'group' => 'site',
                'name' => 'favicon',
                'is_asset' => true,
                'value' => 'assets/favicon/favicon.png',
            ]
        ];
        
        DB::table('preferences')->insert($data);
    }
}
