<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppfeaturesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('appfeatures')->delete();
        
        \DB::table('appfeatures')->insert(array (
            0 => 
            array (
                'featureID' => 7,
                'featureName' => 'Contact',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-smile',
                'featurePath' => '/contact',
                'featureActive' => 1,
                'created_at' => '2025-04-07 13:56:34',
                'updated_at' => '2025-04-07 15:54:55',
                'custom_permission' => '"Listing Access:Own, Global, Group"',
            ),
            1 => 
            array (
                'featureID' => 10,
                'featureName' => 'Invoice',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-share-boxed',
                'featurePath' => '/invoice',
                'featureActive' => 1,
                'created_at' => '2025-04-07 14:37:42',
                'updated_at' => '2025-04-07 14:37:42',
                'custom_permission' => NULL,
            ),
            2 => 
            array (
                'featureID' => 11,
                'featureName' => 'Payment',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-dollar',
                'featurePath' => '/payment',
                'featureActive' => 1,
                'created_at' => '2025-04-07 14:38:16',
                'updated_at' => '2025-04-07 14:38:16',
                'custom_permission' => NULL,
            ),
            3 => 
            array (
                'featureID' => 12,
                'featureName' => 'Contract',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-indent-increase',
                'featurePath' => '/contract',
                'featureActive' => 1,
                'created_at' => '2025-04-07 14:39:04',
                'updated_at' => '2025-04-07 14:39:04',
                'custom_permission' => NULL,
            ),
            4 => 
            array (
                'featureID' => 14,
                'featureName' => 'Project',
                'featureDescription' => NULL,
                'featureIcon' => 'cil-book',
                'featurePath' => '/project',
                'featureActive' => 1,
                'created_at' => '2025-04-07 16:14:34',
                'updated_at' => '2025-04-07 16:14:34',
                'custom_permission' => NULL,
            ),
        ));
        
        
    }
}