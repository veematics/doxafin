<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'featureID' => 7,
                'featureName' => 'Client',
                'featureDescription' => null,
                'featureIcon' => 'cil-smile',
                'featurePath' => '/client',
                'featureActive' => 1,
                'created_at' => '2025-04-07 13:56:34',
                'updated_at' => '2025-04-11 10:30:05',
                'custom_permission' => '"Listing Access:Own, Global, Group"'
            ],
            [
                'featureID' => 10,
                'featureName' => 'Invoice',
                'featureDescription' => null,
                'featureIcon' => 'cil-share-boxed',
                'featurePath' => '/invoice',
                'featureActive' => 1,
                'created_at' => '2025-04-07 14:37:42',
                'updated_at' => '2025-04-07 14:37:42',
                'custom_permission' => null
            ],
            [
                'featureID' => 11,
                'featureName' => 'Payment',
                'featureDescription' => null,
                'featureIcon' => 'cil-dollar',
                'featurePath' => '/payment',
                'featureActive' => 1,
                'created_at' => '2025-04-07 14:38:16',
                'updated_at' => '2025-04-07 14:38:16',
                'custom_permission' => null
            ],
            [
                'featureID' => 12,
                'featureName' => 'Purchase Order',
                'featureDescription' => null,
                'featureIcon' => 'cil-indent-increase',
                'featurePath' => '/po',
                'featureActive' => 1,
                'created_at' => '2025-04-07 14:39:04',
                'updated_at' => '2025-04-11 10:30:19',
                'custom_permission' => null
            ],
            [
                'featureID' => 14,
                'featureName' => 'Project',
                'featureDescription' => null,
                'featureIcon' => 'cil-book',
                'featurePath' => '/project',
                'featureActive' => 1,
                'created_at' => '2025-04-07 16:14:34',
                'updated_at' => '2025-04-07 16:14:34',
                'custom_permission' => null
            ],
            [
                'featureID' => 15,
                'featureName' => 'Inbox',
                'featureDescription' => null,
                'featureIcon' => 'cil-envelope-closed',
                'featurePath' => '/inbox',
                'featureActive' => 1,
                'created_at' => '2025-04-10 22:01:27',
                'updated_at' => '2025-04-10 22:01:27',
                'custom_permission' => null
            ]
        ];

        DB::table('appfeatures')->insert($features);
    }
}