<?php

namespace Database\Seeders;

use App\Models\AppSetup;
use Illuminate\Database\Seeder;

class AppSetupSeeder extends Seeder
{
    public function run()
    {
        AppSetup::create([
            'AppsName' => 'DoxaApp',
            'AppsTitle' => 'Doxa Application',
            'AppsSubTitle' => 'Enterprise Application',
        ]);
    }
}