<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inbox_messages', function (Blueprint $table) {
            $table->dropForeign(['message_category_id']);
            $table->dropColumn('message_category_id');
            $table->string('message_category')->nullable()->after('priority_status');
        });
    }

    public function down()
    {
        Schema::table('inbox_messages', function (Blueprint $table) {
            $table->dropColumn('message_category');
            $table->unsignedBigInteger('message_category_id')->nullable();
            $table->foreign('message_category_id')->references('id')->on('message_categories');
        });
    }
};