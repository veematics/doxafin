<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppFeatureSeeder extends Seeder
{
    public function run()
    {
        $features = [
            [
                'featureName' => 'User Management',
                'featureDescription' => 'Manage system users and their roles',
                'featureIcon' => 'cil-people',
                'featureActive' => true,
                'featurePath' => 'appsetting/user',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'featureName' => 'Menu Builder',
                'featureDescription' => 'Manage application menus and navigation',
                'featureIcon' => 'cil-menu',
                'featureActive' => true,
                'featurePath' => 'appsetting/menu',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'featureName' => 'App Setup',
                'featureDescription' => 'Configure application settings',
                'featureIcon' => 'cil-settings',
                'featureActive' => true,
                'featurePath' => 'appsetting/appsetup',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'featureName' => 'Feature Management',
                'featureDescription' => 'Manage application features',
                'featureIcon' => 'cil-featured-playlist',
                'featureActive' => true,
                'featurePath' => 'appsetting/appfeature',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'featureName' => 'Territory Management',
                'featureDescription' => 'Manage territories and regions',
                'featureIcon' => 'cil-vector',
                'featureActive' => true,
                'featurePath' => 'appsetting/territory',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('appfeatures')->insert($features);
    }
}