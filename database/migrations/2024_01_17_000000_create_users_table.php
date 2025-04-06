<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('avatar')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->tinyInteger('status')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'status')) {
                    $table->tinyInteger('status')->nullable()->after('email_verified_at');
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('remember_token');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}