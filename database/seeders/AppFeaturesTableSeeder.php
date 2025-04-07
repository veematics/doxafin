<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppFeaturesTableSeeder extends Seeder
{
    public function run()
    {
        // Paste your data here in this format
        $features = [
            // Copy the data from tinker output and format it like this:
                [
                    {
                      +"featureID": 1,
                      +"featureName": "Invoice",
                      +"featureIcon": "cil-3d", 
                      +"featurePath": "/test",  
                      +"featureActive": 1,      
                      +"created_at": "2025-04-07 04:29:31",
                      +"updated_at": "2025-04-07 04:30:49",
                      +"custom_permission": null,
                    },
                    {
                      +"featureID": 2,
                      +"featureName": "Contract",
                      +"featureIcon": "cil-aperture",
                      +"featurePath": "132",
                      +"featureActive": 1,
                      +"created_at": "2025-04-07 04:31:02",
                      +"updated_at": "2025-04-07 04:31:02",
                      +"custom_permission": null,
                    },
                    {
                      +"featureID": 3,
                      +"featureName": "Client",
                      +"featureIcon": "cil-people",
                      +"featurePath": "123",
                      +"featureActive": 1,
                      +"created_at": "2025-04-07 04:31:43",
                      +"updated_at": "2025-04-07 11:47:44",
                      +"custom_permission": "Special_View:Own, Global, Territories",
                    },
                    {
                      +"featureID": 4,
                      +"featureName": "Payment",
                      +"featureIcon": "cil-dollar",
                      +"featurePath": "123",
                      +"featureActive": 1,
                      +"created_at": "2025-04-07 04:32:51",
                      +"updated_at": "2025-04-07 04:32:51",
                      +"custom_permission": null,
                    },
                    {
                      +"featureID": 5,
                      +"featureName": "Invoice",
                      +"featureIcon": "cil-3d",
                      +"featurePath": "/test",
                      +"featureActive": 1,
                      +"created_at": "2025-04-07 11:55:59",
                      +"updated_at": "2025-04-07 11:55:59",
                      +"custom_permission": null,
                    },
                    {
                      +"featureID": 6,
                      +"featureName": "Contract",
                      +"featureIcon": "cil-aperture",
                      +"featurePath": "132",
                      +"featureActive": 1,
                      +"created_at": "2025-04-07 11:55:59",
                      +"updated_at": "2025-04-07 11:55:59",
                      +"custom_permission": null,
                    },
                    {
                      +"featureID": 7,
                      +"featureName": "Client",
                      +"featureIcon": "cil-people",
                      +"featurePath": "123",
                      +"featureActive": 1,
                      +"created_at": "2025-04-07 11:55:59",
                      +"updated_at": "2025-04-07 11:55:59",
                      +"custom_permission": "Special_View:Own, Global, Territories",
                    },
                    {
                      +"featureID": 8,
                      +"featureName": "Payment",
                      +"featureIcon": "cil-dollar",
                      +"featurePath": "123",
                      +"featureActive": 1,
                      +"created_at": "2025-04-07 11:55:59",
                      +"updated_at": "2025-04-07 11:55:59",
                      +"custom_permission": null,
                    }
                  ]
            // Add more features...
        ];

        DB::table('appfeatures')->insert($features);
    }
}
