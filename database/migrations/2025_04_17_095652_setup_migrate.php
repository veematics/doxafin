<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // --- Tables without Foreign Key Dependencies (or only self-referencing) ---

        // Create appfeatures table
        if (!Schema::hasTable('appfeatures')) {
            Schema::create('appfeatures', function (Blueprint $table) {
                $table->id('featureID'); // Use id() which creates unsigned big integer primary key
                $table->string('featureName');
                $table->string('featureDescription')->nullable();
                $table->string('featureIcon');
                $table->string('featurePath');
                $table->boolean('featureActive')->default(true);
                $table->timestamps(); // Adds created_at and updated_at
                $table->text('custom_permission')->nullable(); // Use text for potentially longer JSON strings
            });
            // Note: AUTO_INCREMENT is handled by Laravel's id() or bigIncrements()
        }

        // Create app_setups table
        if (!Schema::hasTable('app_setups')) {
            Schema::create('app_setups', function (Blueprint $table) {
                $table->id('AppsID');
                $table->string('AppsName');
                $table->string('AppsTitle');
                $table->string('AppsSubTitle')->nullable();
                $table->string('AppsLogo')->nullable();
                $table->string('AppsShortLogo')->nullable();
                $table->timestamps();
            });
        }

        // Create cache table (Standard Laravel table)
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        // Create cache_locks table (Standard Laravel table)
        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        // Create failed_jobs table (Standard Laravel table)
        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        // Create jobs table (Standard Laravel table)
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        // Create job_batches table (Standard Laravel table)
        if (!Schema::hasTable('job_batches')) {
            Schema::create('job_batches', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('name');
                $table->integer('total_jobs');
                $table->integer('pending_jobs');
                $table->integer('failed_jobs');
                $table->longText('failed_job_ids');
                $table->mediumText('options')->nullable();
                $table->integer('cancelled_at')->nullable();
                $table->integer('created_at');
                $table->integer('finished_at')->nullable();
            });
        }

        // Create menus table
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('Human-readable name for the menu (e.g., "Admin Sidebar")');
                $table->text('description')->nullable()->comment('Optional description for the menu');
                $table->timestamps();
            });
        }

        // Create migrations table (Standard Laravel table - usually exists)
        if (!Schema::hasTable('migrations')) {
             Schema::create('migrations', function (Blueprint $table) {
                $table->increments('id'); // Use increments for standard integer primary key
                $table->string('migration');
                $table->integer('batch');
            });
        }

        // Create password_reset_tokens table (Standard Laravel table)
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // Create roles table
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Create users table (Standard Laravel table, modified)
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('avatar')->nullable(); // Added avatar
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->boolean('is_active')->default(true); // Added is_active
                $table->timestamps();
            });
        }

        // Create sessions table (Standard Laravel table)
        if (!Schema::hasTable('sessions')) {
             Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index(); // Uses constrained() if users table uses default 'id'
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
             });
        }


        // --- Tables with Foreign Key Dependencies ---

        // Create feature_role table
        if (!Schema::hasTable('feature_role')) {
            Schema::create('feature_role', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('feature_id');
                $table->unsignedBigInteger('role_id');
                $table->boolean('can_view_own')->default(false);
                $table->boolean('can_view_roles')->default(false);
                $table->boolean('can_view_all')->default(false);
                $table->boolean('can_view')->default(false); // Changed default based on SQL
                $table->tinyInteger('can_add')->default(0); // Changed default based on SQL
                $table->boolean('can_create')->default(false);
                $table->boolean('can_edit')->default(false);
                $table->boolean('can_delete')->default(false);
                $table->boolean('can_approve')->default(false);
                $table->json('additional_permissions')->nullable();
                $table->timestamps();

                $table->unique(['feature_id', 'role_id']); // Unique constraint

                // Foreign Keys
                $table->foreign('feature_id')->references('featureID')->on('appfeatures')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        }

        // Create inbox_messages table
        if (!Schema::hasTable('inbox_messages')) {
            Schema::create('inbox_messages', function (Blueprint $table) {
                $table->id();
                $table->string('subject');
                $table->text('message');
                $table->unsignedBigInteger('message_parent_id')->nullable();
                $table->boolean('is_read')->default(false);
                $table->tinyInteger('priority_status')->default(2); // 1=Low, 2=Normal, 3=High ?
                $table->unsignedBigInteger('sent_from')->nullable();
                $table->unsignedBigInteger('sent_to');
                $table->timestamps();
                $table->softDeletes(); // Adds deleted_at column
                $table->string('message_category', 50)->nullable();

                // Foreign Keys (including self-referencing)
                $table->foreign('message_parent_id')->references('id')->on('inbox_messages')->onDelete('cascade');
                $table->foreign('sent_from')->references('id')->on('users')->onDelete('cascade'); // Assuming SET NULL might be better if user deleted? Cascade is from SQL dump.
                $table->foreign('sent_to')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Create menu_items table
        if (!Schema::hasTable('menu_items')) {
            Schema::create('menu_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_id')->index();
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->enum('item_type', ['feature', 'free_form']);
                $table->unsignedInteger('order')->default(0);
                $table->string('title');
                $table->string('icon', 100)->nullable();
                $table->string('path', 2048)->nullable(); // Increased length based on SQL
                $table->string('target', 50)->default('_self');
                $table->unsignedBigInteger('app_feature_id')->nullable()->index();
                $table->json('custom_data')->nullable();
                $table->timestamps();

                // Foreign Keys (including self-referencing)
                $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
                $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
                // Note: No direct foreign key constraint for app_feature_id in the provided SQL,
                // but you might want to add one if it should always reference appfeatures:
                // $table->foreign('app_feature_id')->references('featureID')->on('appfeatures')->onDelete('set null'); // or cascade
            });
        }

        // Create role_user table
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps(); // Timestamps were missing in SQL but are standard practice

                $table->unique(['role_id', 'user_id']); // Unique constraint

                // Foreign Keys
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop tables in reverse order of creation to respect foreign keys
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('menu_items'); // Drop before menus and appfeatures due to potential FK
        Schema::dropIfExists('inbox_messages'); // Drop before users due to FK
        Schema::dropIfExists('feature_role'); // Drop before appfeatures and roles due to FK

        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('migrations');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('app_setups');
        Schema::dropIfExists('appfeatures');
    }
};
