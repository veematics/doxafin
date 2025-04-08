<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('feature_role', 'can_view_own')) {
            Schema::table('feature_role', function (Blueprint $table) {
                $table->boolean('can_view_own')->default(false)->after('role_id');
                $table->boolean('can_view_roles')->default(false)->after('can_view_own');
                $table->boolean('can_view_all')->default(false)->after('can_view_roles');
            });
        }
    }

    public function down()
    {
        Schema::table('feature_role', function (Blueprint $table) {
            $table->dropColumn(['can_view_own', 'can_view_roles', 'can_view_all']);
        });
    }
};