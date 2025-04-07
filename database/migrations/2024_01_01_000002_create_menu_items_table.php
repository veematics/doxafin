<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->comment('Foreign key referencing the menus table')
                ->constrained('menus')
                ->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->comment('Foreign key referencing itself for nesting (NULL for top-level items)')
                ->constrained('menu_items')
                ->cascadeOnDelete();
            $table->enum('item_type', ['feature', 'free_form'])->comment('Type of menu item');
            $table->unsignedInteger('order')->default(0)->comment('Order of the item within its parent level');
            $table->string('title')->comment('Display text for the menu item');
            $table->string('icon', 100)->nullable()->comment('Icon class or identifier (e.g., CoreUI icon class like "cil-speedometer")');
            $table->string('path', 2048)->nullable()->comment('URL/Link for the menu item (especially for free_form)');
            $table->string('target', 50)->default('_self')->comment('Link target (e.g., _blank, _self)');
            $table->unsignedBigInteger('app_feature_id')->nullable()->comment('Optional foreign key referencing app_features if item_type is "feature"');
            $table->json('custom_data')->nullable()->comment('Optional JSON field for extra data if needed');
            $table->timestamps();

            $table->index('menu_id');
            $table->index('parent_id');
            $table->index('app_feature_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
};