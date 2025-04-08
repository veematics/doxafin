<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Viktor Iwan',
                'email' => 'viktor.iwan@doxadigital.com',
                'avatar' => '1744009314_67f3786202cb9.jpeg',
                'email_verified_at' => NULL,
                'password' => '$2y$12$/KhKP8OdxwsUvvrjq31SdeYrrpj82dqSZOCVEJanzUD2qNuDaj9su',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-03 03:46:14',
                'updated_at' => '2025-04-07 07:04:09',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Ratna Anni',
                'email' => 'ratna@doxadigital.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$5i1pvS9i3DtBTY1sZ8X8hupJGVhCUH4UMG.epccHXii/hWO/ENL86',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 06:41:34',
                'updated_at' => '2025-04-07 06:41:47',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Marcelina Powlowski III',
                'email' => 'marcelina-powlowski-iii@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$TbwCTvBk5CsXRdFsuHj0qupvmVyfFzsTFZWNgwYvQcCaeBxUa1b3q',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:48:58',
                'updated_at' => '2025-04-07 16:48:58',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Rusty Windler V',
                'email' => 'rusty-windler-v@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$fBgbsHvaCStbBOpIlQmDSOWP4xN3l.fEh295g7s8lHii2w9CbqVDi',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:48:58',
                'updated_at' => '2025-04-07 16:48:58',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Lavonne Dach',
                'email' => 'lavonne-dach@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$drY3BRQ1QADvdjnnXEjxJe8sYBdMnnvI7sbO.aV/YPy9SiQvcUcHS',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:48:58',
                'updated_at' => '2025-04-07 16:48:58',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Rhiannon Kuhlman',
                'email' => 'rhiannon-kuhlman@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$xN49AwQgQKmuZUj2QWxv9eDa0Ii/uoQ8YSyFlaQ1zUjv/im63CjXm',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:48:59',
                'updated_at' => '2025-04-07 16:48:59',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Colby Koch V',
                'email' => 'colby-koch-v@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$hMa4gnf8NUGwwXXG28D0WuAcjZSqh3ovE09pBP1rYcfAMB6WI1isC',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:48:59',
                'updated_at' => '2025-04-07 16:48:59',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Mr. Garnet Grant II',
                'email' => 'mr-garnet-grant-ii@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$a8Zt.VofF8cROeQn7VrJIeQb/S6aGvNjsUBTnwJv2Jtu1xjpqYf7S',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:48:59',
                'updated_at' => '2025-04-07 16:48:59',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Maureen Wuckert',
                'email' => 'maureen-wuckert@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$jXNhz6g9nq/OQl/otaPR8OKqEYDw0xQ/JDLTSnxbtpxpNYvcbn/eq',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:48:59',
                'updated_at' => '2025-04-07 16:48:59',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Dexter Funk',
                'email' => 'dexter-funk@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$WaFHeIqM0jFRmtYSgRrZ/umuticyMj6s2hnQM2DcDBAarMPRcr.yu',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:48:59',
                'updated_at' => '2025-04-07 16:48:59',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Alyson Witting',
                'email' => 'alyson-witting@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$H6AtFeKOvXKr9jzBC7g1ieBx2oFse0txTPrTK2kizdFw1/RxxJ7rK',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:49:00',
                'updated_at' => '2025-04-07 16:49:00',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Jeramie Carroll',
                'email' => 'jeramie-carroll@example.com',
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$GNsCOWCVgIeAAZIDTc0qD.xUoGZ6jQuHQfv5LH2DWu.l729uSMNy2',
                'remember_token' => NULL,
                'is_active' => 1,
                'created_at' => '2025-04-07 16:49:00',
                'updated_at' => '2025-04-07 16:49:00',
            ),
        ));
        
        
    }
}