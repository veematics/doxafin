<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppfeaturesTableSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('appfeatures')) {
            return;
        }

        DB::table('appfeatures')->truncate();
        
        $features = [
            [
                'featureID' => 7,
                'featureName' => 'Contact',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-smile',
                'featurePath' => '/contact',
                'featureActive' => 1,
                'created_at' => '2025-04-07 13:56:34',
                'updated_at' => '2025-04-07 15:54:55',
                'custom_permission' => 'Listing Access:Own, Global, Group',
            ],
            [
                'featureID' => 10,
                'featureName' => 'Invoice',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-share-boxed',
                'featurePath' => '/invoice',
                'featureActive' => 1,
                'created_at' => '2025-04-07 14:37:42',
                'updated_at' => '2025-04-07 14:37:42',
                'custom_permission' => NULL,
            ],
            [
                'featureID' => 11,
                'featureName' => 'Payment',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-dollar',
                'featurePath' => '/payment',
                'featureActive' => 1,
                'created_at' => '2025-04-07 14:38:16',
                'updated_at' => '2025-04-07 14:38:16',
                'custom_permission' => NULL,
            ],
            [
                'featureID' => 12,
                'featureName' => 'Contract',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-indent-increase',
                'featurePath' => '/contract',
                'featureActive' => 1,
                'created_at' => '2025-04-07 14:39:04',
                'updated_at' => '2025-04-07 14:39:04',
                'custom_permission' => NULL,
            ],
            [
                'featureID' => 14,
                'featureName' => 'Project',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-book',
                'featurePath' => '/project',
                'featureActive' => 1,
                'created_at' => '2025-04-07 16:14:34',
                'updated_at' => '2025-04-07 16:14:34',
                'custom_permission' => NULL,
            ],
        ];

        DB::table('appfeatures')->insert($features);
    }
}