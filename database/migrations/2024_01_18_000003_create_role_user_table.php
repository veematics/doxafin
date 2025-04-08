<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();

                $table->foreign('role_id')
                      ->references('id')
                      ->on('roles')
                      ->onDelete('cascade');
                
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');

                $table->unique(['role_id', 'user_id']);
            });
            return;
        }

        // If table exists, check and add missing columns
        if (!Schema::hasColumn('role_user', 'role_id')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->foreign('role_id')
                      ->references('id')
                      ->on('roles')
                      ->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('role_user', 'user_id')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('role_user', 'created_at')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    private function hasUniqueConstraint($table, $columns)
    {
        $conn = Schema::getConnection();
        $dbSchemaManager = $conn->getDoctrineSchemaManager();
        $indexes = $dbSchemaManager->listTableIndexes($table);
        
        $uniqueIndex = implode('_', array_merge([$table], $columns, ['unique']));
        
        return isset($indexes[$uniqueIndex]);
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};