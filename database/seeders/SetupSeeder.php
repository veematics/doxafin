<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Import Hash facade for password hashing

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to avoid order issues during seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables before seeding (optional, but good for repeatable seeding)
        // Be cautious with truncate in production if tables have existing important data
        DB::table('appfeatures')->truncate();
        DB::table('app_setups')->truncate();
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('menus')->truncate();
        DB::table('menu_items')->truncate();
        DB::table('feature_role')->truncate();
        DB::table('role_user')->truncate();
        // Do NOT truncate cache, sessions, migrations etc.

        // --- Seed Data ---

        // Seed appfeatures
        DB::table('appfeatures')->insert([
            [
                'featureID' => 7,
                'featureName' => 'Client',
                'featureDescription' => null,
                'featureIcon' => 'cil-smile',
                'featurePath' => '/client',
                'featureActive' => 1,
                'created_at' => '2025-04-07 13:56:34',
                'updated_at' => '2025-04-11 10:30:05',
                'custom_permission' => '"Listing Access:Own, Global, Group"' // Store as plain text/JSON string
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
            ],
            // Add feature IDs 2, 3, 4, 5, 6 based on menu_items data if they exist
             [
                'featureID' => 2, // Assuming based on menu_items
                'featureName' => 'User Management Feature', // Placeholder name
                'featureDescription' => 'Manages users', // Placeholder description
                'featureIcon' => 'cil-people', // From menu_items
                'featurePath' => 'appsetting/users', // From menu_items
                'featureActive' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'custom_permission' => null
            ],
            [
                'featureID' => 3, // Assuming based on menu_items
                'featureName' => 'Menu Builder Feature', // Placeholder name
                'featureDescription' => 'Builds menus', // Placeholder description
                'featureIcon' => 'cil-menu', // From menu_items
                'featurePath' => 'appsetting/menu', // From menu_items
                'featureActive' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'custom_permission' => null
            ],
             [
                'featureID' => 4, // Assuming based on menu_items
                'featureName' => 'App Setup Feature', // Placeholder name
                'featureDescription' => 'Application setup', // Placeholder description
                'featureIcon' => 'cil-settings', // From menu_items
                'featurePath' => 'appsetting/appsetup', // From menu_items
                'featureActive' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'custom_permission' => null
            ],
            [
                'featureID' => 5, // Assuming based on menu_items
                'featureName' => 'Feature Management Feature', // Placeholder name
                'featureDescription' => 'Manages app features', // Placeholder description
                'featureIcon' => 'cil-featured-playlist', // From menu_items
                'featurePath' => 'appsetting/appfeature', // From menu_items
                'featureActive' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'custom_permission' => null
            ],
             [
                'featureID' => 6, // Assuming based on menu_items
                'featureName' => 'Role Management Feature', // Placeholder name
                'featureDescription' => 'Manages roles', // Placeholder description
                'featureIcon' => 'cil-vector', // From menu_items
                'featurePath' => 'appsetting/roles', // From menu_items
                'featureActive' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'custom_permission' => null
            ],
        ]);

        // Seed app_setups
        DB::table('app_setups')->insert([
            [
                'AppsID' => 1,
                'AppsName' => 'Doxa360',
                'AppsTitle' => 'Doxa Application',
                'AppsSubTitle' => 'Enterprise Application',
                'AppsLogo' => '1744009508_logo.png',
                'AppsShortLogo' => '1744009508_short.png',
                'created_at' => '2025-04-07 06:17:34',
                'updated_at' => '2025-04-07 07:05:08'
            ]
        ]);

        // Seed users
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Viktor Iwan',
                'email' => 'viktor.iwan@doxadigital.com',
                'avatar' => '1744009314_67f3786202cb9.jpeg',
                'email_verified_at' => null,
                // IMPORTANT: Hash the password! Never store plain text passwords.
                // Replace 'password' with the actual desired default password.
                'password' => Hash::make('password'), // Example: Hashing 'password'
                'remember_token' => null,
                'is_active' => 1,
                'created_at' => '2025-04-03 03:46:14',
                'updated_at' => '2025-04-07 07:04:09'
            ]
            // Add other users if necessary
        ]);

        // Seed roles
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'AE',
                'display_name' => 'Account Executive',
                'description' => null,
                'created_at' => '2025-04-07 16:01:03',
                'updated_at' => '2025-04-07 16:01:03'
            ],
            [
                'id' => 2,
                'name' => 'FIN',
                'display_name' => 'Finance and Accounting',
                'description' => null,
                'created_at' => '2025-04-07 16:45:35',
                'updated_at' => '2025-04-07 16:45:55'
            ],
            [
                'id' => 3,
                'name' => 'SA',
                'display_name' => 'Super Admin',
                'description' => null,
                'created_at' => '2025-04-07 18:34:33',
                'updated_at' => '2025-04-07 18:34:33'
            ]
        ]);

        // Seed menus
        DB::table('menus')->insert([
            [
                'id' => 6,
                'name' => 'Sidebar Menu',
                'description' => 'Main sidebar navigation menu',
                'created_at' => '2025-04-07 13:28:27',
                'updated_at' => '2025-04-07 13:28:27'
            ],
            [
                'id' => 7,
                'name' => 'Personal Menu',
                'description' => 'Personal user navigation menu',
                'created_at' => '2025-04-07 13:28:27',
                'updated_at' => '2025-04-07 13:28:27'
            ],
            [
                'id' => 8,
                'name' => 'Super Admin',
                'description' => 'Superadmin',
                'created_at' => '2025-04-07 13:28:27',
                'updated_at' => '2025-04-07 13:28:27'
            ]
        ]);

        // Seed menu_items
        // Insert parent items first if possible, or handle dependencies carefully
        DB::table('menu_items')->insert([
            // Menu ID 8 (Super Admin)
            ['id' => 33, 'menu_id' => 8, 'parent_id' => null, 'item_type' => 'feature', 'order' => 0, 'title' => 'App Setup', 'icon' => 'cil-settings', 'path' => 'appsetting/appsetup', 'target' => '_self', 'app_feature_id' => 4, 'custom_data' => null, 'created_at' => '2025-04-10 22:01:43', 'updated_at' => '2025-04-10 22:01:43'],
            ['id' => 34, 'menu_id' => 8, 'parent_id' => null, 'item_type' => 'feature', 'order' => 1, 'title' => 'Feature Management', 'icon' => 'cil-featured-playlist', 'path' => 'appsetting/appfeature', 'target' => '_self', 'app_feature_id' => 5, 'custom_data' => null, 'created_at' => '2025-04-10 22:01:43', 'updated_at' => '2025-04-10 22:01:43'],
            ['id' => 35, 'menu_id' => 8, 'parent_id' => null, 'item_type' => 'feature', 'order' => 2, 'title' => 'Menu Builder', 'icon' => 'cil-menu', 'path' => 'appsetting/menu', 'target' => '_self', 'app_feature_id' => 3, 'custom_data' => null, 'created_at' => '2025-04-10 22:01:43', 'updated_at' => '2025-04-10 22:01:43'],
            ['id' => 36, 'menu_id' => 8, 'parent_id' => null, 'item_type' => 'feature', 'order' => 3, 'title' => 'Role Management', 'icon' => 'cil-vector', 'path' => 'appsetting/roles', 'target' => '_self', 'app_feature_id' => 6, 'custom_data' => null, 'created_at' => '2025-04-10 22:01:43', 'updated_at' => '2025-04-10 22:01:43'],
            ['id' => 37, 'menu_id' => 8, 'parent_id' => null, 'item_type' => 'feature', 'order' => 4, 'title' => 'User Management', 'icon' => 'cil-people', 'path' => 'appsetting/users', 'target' => '_self', 'app_feature_id' => 2, 'custom_data' => null, 'created_at' => '2025-04-10 22:01:43', 'updated_at' => '2025-04-10 22:01:43'],
            ['id' => 38, 'menu_id' => 8, 'parent_id' => null, 'item_type' => 'feature', 'order' => 5, 'title' => 'Inbox', 'icon' => 'cil-envelope-closed', 'path' => '/inbox', 'target' => '_self', 'app_feature_id' => 15, 'custom_data' => null, 'created_at' => '2025-04-10 22:01:43', 'updated_at' => '2025-04-10 22:01:43'],

            // Menu ID 7 (Personal Menu)
            ['id' => 39, 'menu_id' => 7, 'parent_id' => null, 'item_type' => 'free_form', 'order' => 0, 'title' => 'Profile', 'icon' => 'cil-user', 'path' => '/profile', 'target' => '_self', 'app_feature_id' => null, 'custom_data' => null, 'created_at' => '2025-04-11 03:02:45', 'updated_at' => '2025-04-11 03:02:45'],
            ['id' => 40, 'menu_id' => 7, 'parent_id' => null, 'item_type' => 'feature', 'order' => 1, 'title' => 'Inbox', 'icon' => 'cil-envelope-closed', 'path' => '/inbox', 'target' => '_self', 'app_feature_id' => 15, 'custom_data' => null, 'created_at' => '2025-04-11 03:02:45', 'updated_at' => '2025-04-11 03:02:45'],

            // Menu ID 6 (Sidebar Menu) - Including potential child items based on cache data structure
             ['id' => 55, 'menu_id' => 6, 'parent_id' => null, 'item_type' => 'feature', 'order' => 0, 'title' => 'Client', 'icon' => 'cil-smile', 'path' => '/client', 'target' => '_self', 'app_feature_id' => 7, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 56, 'menu_id' => 6, 'parent_id' => 55, 'item_type' => 'free_form', 'order' => 0, 'title' => 'Add Client', 'icon' => 'cil-address-book', 'path' => '/client/add', 'target' => '_self', 'app_feature_id' => 7, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 57, 'menu_id' => 6, 'parent_id' => 55, 'item_type' => 'free_form', 'order' => 1, 'title' => 'Client List', 'icon' => 'cil-playlist-add', 'path' => '/client/list', 'target' => '_self', 'app_feature_id' => 7, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 58, 'menu_id' => 6, 'parent_id' => null, 'item_type' => 'feature', 'order' => 1, 'title' => 'Purchase Order', 'icon' => 'cil-indent-increase', 'path' => '/po', 'target' => '_self', 'app_feature_id' => 12, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 59, 'menu_id' => 6, 'parent_id' => 58, 'item_type' => 'free_form', 'order' => 0, 'title' => 'Add PO', 'icon' => 'cil-library-add', 'path' => '/po/add', 'target' => '_self', 'app_feature_id' => 12, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 60, 'menu_id' => 6, 'parent_id' => 58, 'item_type' => 'free_form', 'order' => 1, 'title' => 'PO List', 'icon' => 'cil-list', 'path' => '/po/list', 'target' => '_self', 'app_feature_id' => 12, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 61, 'menu_id' => 6, 'parent_id' => null, 'item_type' => 'feature', 'order' => 2, 'title' => 'Project', 'icon' => 'cil-book', 'path' => '/project', 'target' => '_self', 'app_feature_id' => 14, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 62, 'menu_id' => 6, 'parent_id' => 61, 'item_type' => 'free_form', 'order' => 0, 'title' => 'Project List', 'icon' => 'cil-list-rich', 'path' => '/project/list', 'target' => '_self', 'app_feature_id' => 14, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 63, 'menu_id' => 6, 'parent_id' => null, 'item_type' => 'feature', 'order' => 3, 'title' => 'Invoice', 'icon' => 'cil-share-boxed', 'path' => '/invoice', 'target' => '_self', 'app_feature_id' => 10, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 64, 'menu_id' => 6, 'parent_id' => 63, 'item_type' => 'free_form', 'order' => 0, 'title' => 'Add Invoice', 'icon' => 'cil-library-add', 'path' => '/invoice/add', 'target' => '_self', 'app_feature_id' => 10, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 65, 'menu_id' => 6, 'parent_id' => 63, 'item_type' => 'free_form', 'order' => 1, 'title' => 'Invoice list', 'icon' => 'cil-list', 'path' => '/invoice/list', 'target' => '_self', 'app_feature_id' => 10, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 66, 'menu_id' => 6, 'parent_id' => null, 'item_type' => 'feature', 'order' => 4, 'title' => 'Payment', 'icon' => 'cil-dollar', 'path' => '/payment', 'target' => '_self', 'app_feature_id' => 11, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 67, 'menu_id' => 6, 'parent_id' => 66, 'item_type' => 'free_form', 'order' => 0, 'title' => 'Add payment', 'icon' => 'cil-library-add', 'path' => '/payment/add', 'target' => '_self', 'app_feature_id' => 11, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],
             ['id' => 68, 'menu_id' => 6, 'parent_id' => 66, 'item_type' => 'free_form', 'order' => 1, 'title' => 'Payment List', 'icon' => 'cil-list', 'path' => '/payment/list', 'target' => '_self', 'app_feature_id' => 11, 'custom_data' => null, 'created_at' => '2025-04-11 10:55:45', 'updated_at' => '2025-04-11 10:55:45'],

            // Latest addition from SQL dump
            ['id' => 83, 'menu_id' => 6, 'parent_id' => null, 'item_type' => 'feature', 'order' => 0, 'title' => 'Sample Menu', 'icon' => 'cil-smile', 'path' => '/client', 'target' => '_self', 'app_feature_id' => 7, 'custom_data' => null, 'created_at' => '2025-04-17 09:29:54', 'updated_at' => '2025-04-17 09:29:54'],

        ]);

        // Seed feature_role (Permissions)
        DB::table('feature_role')->insert([
            // Role 3 (SA)
            ['id' => 110, 'feature_id' => 7, 'role_id' => 3, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 0, 'can_add' => 0, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-11 11:51:58', 'updated_at' => '2025-04-11 11:51:58'],
            ['id' => 111, 'feature_id' => 10, 'role_id' => 3, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 0, 'can_add' => 0, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-11 11:51:58', 'updated_at' => '2025-04-11 11:51:58'],
            ['id' => 112, 'feature_id' => 11, 'role_id' => 3, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 0, 'can_add' => 0, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-11 11:51:58', 'updated_at' => '2025-04-11 11:51:58'],
            ['id' => 113, 'feature_id' => 12, 'role_id' => 3, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 0, 'can_add' => 0, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-11 11:51:58', 'updated_at' => '2025-04-11 11:51:58'],
            ['id' => 114, 'feature_id' => 14, 'role_id' => 3, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 0, 'can_add' => 0, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-11 11:51:58', 'updated_at' => '2025-04-11 11:51:58'],
            ['id' => 115, 'feature_id' => 15, 'role_id' => 3, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 0, 'can_add' => 0, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-11 11:51:58', 'updated_at' => '2025-04-11 11:51:58'],

            // Role 2 (FIN)
            ['id' => 145, 'feature_id' => 7, 'role_id' => 2, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 0, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:36', 'updated_at' => '2025-04-13 08:47:36'],
            ['id' => 146, 'feature_id' => 10, 'role_id' => 2, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:36', 'updated_at' => '2025-04-13 08:47:36'],
            ['id' => 147, 'feature_id' => 11, 'role_id' => 2, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 0, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:36', 'updated_at' => '2025-04-13 08:47:36'],
            ['id' => 148, 'feature_id' => 12, 'role_id' => 2, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 0, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:36', 'updated_at' => '2025-04-13 08:47:36'],
            ['id' => 149, 'feature_id' => 14, 'role_id' => 2, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:36', 'updated_at' => '2025-04-13 08:47:36'],
            ['id' => 150, 'feature_id' => 15, 'role_id' => 2, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:36', 'updated_at' => '2025-04-13 08:47:36'],

             // Role 1 (AE)
            ['id' => 151, 'feature_id' => 7, 'role_id' => 1, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 1, 'can_approve' => 0, 'additional_permissions' => json_encode(['listing-access' => 'Own']), 'created_at' => '2025-04-13 08:47:59', 'updated_at' => '2025-04-13 08:47:59'],
            ['id' => 152, 'feature_id' => 10, 'role_id' => 1, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 0, 'can_add' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:59', 'updated_at' => '2025-04-13 08:47:59'],
            ['id' => 153, 'feature_id' => 11, 'role_id' => 1, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:59', 'updated_at' => '2025-04-13 08:47:59'],
            // Note: can_view was 2 in SQL, assuming it means true (1) or perhaps a specific level? Using 1 for boolean.
            ['id' => 154, 'feature_id' => 12, 'role_id' => 1, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 0, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 1, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:59', 'updated_at' => '2025-04-13 08:47:59'],
            ['id' => 155, 'feature_id' => 14, 'role_id' => 1, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 1, 'can_add' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 1, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:59', 'updated_at' => '2025-04-13 08:47:59'],
            ['id' => 156, 'feature_id' => 15, 'role_id' => 1, 'can_view_own' => 0, 'can_view_roles' => 0, 'can_view_all' => 0, 'can_view' => 0, 'can_add' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'additional_permissions' => null, 'created_at' => '2025-04-13 08:47:59', 'updated_at' => '2025-04-13 08:47:59'],

        ]);

        // Seed role_user (Assign Roles to Users)
        DB::table('role_user')->insert([
            [
                'id' => 22, // Use specific ID from SQL dump if needed, otherwise let it auto-increment
                'role_id' => 3, // Super Admin
                'user_id' => 1, // Viktor Iwan
                'created_at' => null, // Or use now()
                'updated_at' => null  // Or use now()
            ]
            // Add other role assignments if necessary
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
