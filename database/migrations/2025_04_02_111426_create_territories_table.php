<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('territories', function (Blueprint $table) {
            $table->id('territory_id');
            $table->string('territory_name');
            $table->unsignedBigInteger('parent_territory_id')->nullable();
            $table->timestamps();
        });

        // Add foreign key after table creation
        Schema::table('territories', function (Blueprint $table) {
            $table->foreign('parent_territory_id')
                  ->references('territory_id')
                  ->on('territories')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('territories');
    }
};