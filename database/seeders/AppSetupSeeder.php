<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSetupSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('app_setups')->insert([
            'AppsID' => 1,
            'AppsName' => 'Doxa360',
            'AppsTitle' => 'Doxa Application',
            'AppsSubTitle' => 'Enterprise Application',
            'AppsLogo' => '1744009508_logo.png',
            'AppsShortLogo' => '1744009508_short.png',
            'created_at' => '2025-04-07 06:17:34',
            'updated_at' => '2025-04-07 07:05:08'
        ]);
    }
}