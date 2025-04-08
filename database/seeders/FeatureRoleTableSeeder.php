<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FeatureRoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('feature_role')->delete();
        
        \DB::table('feature_role')->insert(array (
            0 => 
            array (
                'id' => 33,
                'feature_id' => 7,
                'role_id' => 1,
                'can_view' => 1,
                'can_create' => 1,
                'can_edit' => 1,
                'can_delete' => 1,
                'can_approve' => 0,
                'additional_permissions' => '{"listing-access": "Own"}',
                'created_at' => '2025-04-07 16:44:54',
                'updated_at' => '2025-04-07 16:44:54',
            ),
            1 => 
            array (
                'id' => 34,
                'feature_id' => 10,
                'role_id' => 1,
                'can_view' => 1,
                'can_create' => 0,
                'can_edit' => 0,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 16:44:54',
                'updated_at' => '2025-04-07 16:44:54',
            ),
            2 => 
            array (
                'id' => 35,
                'feature_id' => 11,
                'role_id' => 1,
                'can_view' => 1,
                'can_create' => 0,
                'can_edit' => 0,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 16:44:54',
                'updated_at' => '2025-04-07 16:44:54',
            ),
            3 => 
            array (
                'id' => 36,
                'feature_id' => 12,
                'role_id' => 1,
                'can_view' => 1,
                'can_create' => 1,
                'can_edit' => 1,
                'can_delete' => 1,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 16:44:54',
                'updated_at' => '2025-04-07 16:44:54',
            ),
            4 => 
            array (
                'id' => 37,
                'feature_id' => 14,
                'role_id' => 1,
                'can_view' => 1,
                'can_create' => 1,
                'can_edit' => 1,
                'can_delete' => 1,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 16:44:54',
                'updated_at' => '2025-04-07 16:44:54',
            ),
            5 => 
            array (
                'id' => 43,
                'feature_id' => 7,
                'role_id' => 2,
                'can_view' => 1,
                'can_create' => 0,
                'can_edit' => 0,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 16:45:55',
                'updated_at' => '2025-04-07 16:45:55',
            ),
            6 => 
            array (
                'id' => 44,
                'feature_id' => 10,
                'role_id' => 2,
                'can_view' => 1,
                'can_create' => 1,
                'can_edit' => 1,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 16:45:55',
                'updated_at' => '2025-04-07 16:45:55',
            ),
            7 => 
            array (
                'id' => 45,
                'feature_id' => 11,
                'role_id' => 2,
                'can_view' => 1,
                'can_create' => 1,
                'can_edit' => 1,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 16:45:55',
                'updated_at' => '2025-04-07 16:45:55',
            ),
            8 => 
            array (
                'id' => 46,
                'feature_id' => 12,
                'role_id' => 2,
                'can_view' => 1,
                'can_create' => 0,
                'can_edit' => 0,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 16:45:55',
                'updated_at' => '2025-04-07 16:45:55',
            ),
            9 => 
            array (
                'id' => 47,
                'feature_id' => 14,
                'role_id' => 2,
                'can_view' => 1,
                'can_create' => 0,
                'can_edit' => 0,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 16:45:55',
                'updated_at' => '2025-04-07 16:45:55',
            ),
            10 => 
            array (
                'id' => 63,
                'feature_id' => 7,
                'role_id' => 3,
                'can_view' => 0,
                'can_create' => 0,
                'can_edit' => 1,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 19:12:14',
                'updated_at' => '2025-04-07 19:12:14',
            ),
            11 => 
            array (
                'id' => 64,
                'feature_id' => 10,
                'role_id' => 3,
                'can_view' => 0,
                'can_create' => 0,
                'can_edit' => 0,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 19:12:14',
                'updated_at' => '2025-04-07 19:12:14',
            ),
            12 => 
            array (
                'id' => 65,
                'feature_id' => 11,
                'role_id' => 3,
                'can_view' => 0,
                'can_create' => 0,
                'can_edit' => 1,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 19:12:14',
                'updated_at' => '2025-04-07 19:12:14',
            ),
            13 => 
            array (
                'id' => 66,
                'feature_id' => 12,
                'role_id' => 3,
                'can_view' => 0,
                'can_create' => 0,
                'can_edit' => 1,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 19:12:14',
                'updated_at' => '2025-04-07 19:12:14',
            ),
            14 => 
            array (
                'id' => 67,
                'feature_id' => 14,
                'role_id' => 3,
                'can_view' => 0,
                'can_create' => 0,
                'can_edit' => 1,
                'can_delete' => 0,
                'can_approve' => 0,
                'additional_permissions' => NULL,
                'created_at' => '2025-04-07 19:12:14',
                'updated_at' => '2025-04-07 19:12:14',
            ),
        ));
        
        
    }
}