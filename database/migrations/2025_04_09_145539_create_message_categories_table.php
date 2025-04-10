<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('message_categories', function (Blueprint $table) {
            $table->id();
            $table->string('messageCategoryName');
            $table->timestamps();
        });

        Schema::table('inbox_messages', function (Blueprint $table) {
            $table->foreignId('message_category_id')->nullable()->constrained('message_categories');
        });
    }

    public function down()
    {
        Schema::table('inbox_messages', function (Blueprint $table) {
            $table->dropForeign(['message_category_id']);
            $table->dropColumn('message_category_id');
        });
        Schema::dropIfExists('message_categories');
    }
};