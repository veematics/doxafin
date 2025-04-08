<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'AE',
                'display_name' => 'Account Executive',
                'description' => NULL,
                'created_at' => '2025-04-07 16:01:03',
                'updated_at' => '2025-04-07 16:01:03',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'FIN',
                'display_name' => 'Finance and Accounting',
                'description' => NULL,
                'created_at' => '2025-04-07 16:45:35',
                'updated_at' => '2025-04-07 16:45:55',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'SA',
                'display_name' => 'Super Admin',
                'description' => NULL,
                'created_at' => '2025-04-07 18:34:33',
                'updated_at' => '2025-04-07 18:34:33',
            ),
        ));
        
        
    }
}