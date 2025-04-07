<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('feature_role')) {
            Schema::create('feature_role', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->foreignId('feature_id')->constrained('appfeatures', 'featureID')->onDelete('cascade');
                $table->boolean('can_view')->default(false);
                $table->boolean('can_create')->default(false);
                $table->boolean('can_edit')->default(false);
                $table->boolean('can_delete')->default(false);
                $table->boolean('can_approve')->default(false);
                $table->json('additional_permissions')->nullable();
                $table->timestamps();
            });
        } else if (!Schema::hasColumn('feature_role', 'can_approve')) {
            Schema::table('feature_role', function (Blueprint $table) {
                $table->boolean('can_approve')->default(false)->after('can_delete');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('feature_role', 'can_approve')) {
            Schema::table('feature_role', function (Blueprint $table) {
                $table->dropColumn('can_approve');
            });
        }
    }
};