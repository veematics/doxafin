<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Human-readable name for the menu (e.g., "Admin Sidebar")');
            $table->string('slug')->unique()->comment('Unique identifier slug for the menu (e.g., "admin-sidebar")');
            $table->enum('type', ['sidebar', 'personal'])->comment('Type of the menu');
            $table->text('description')->nullable()->comment('Optional description for the menu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};