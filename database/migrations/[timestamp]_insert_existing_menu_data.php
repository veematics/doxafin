<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $existingMenus = [
            [
                'featureName' => 'Dashboard',
                'featureIcon' => 'cil-speedometer',
                'featurePath' => '/dashboard',
                'featureActive' => 1,
                'custom_permission' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add your other menu items here
        ];

        DB::table('appfeatures')->insert($existingMenus);
    }

    public function down()
    {
        // Optional: You can specify which records to delete in rollback
        DB::table('appfeatures')->whereIn('featureName', [
            'Dashboard',
            // Add other feature names here
        ])->delete();
    }
};