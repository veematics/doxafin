<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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

    public function down()
    {
        Schema::dropIfExists('app_setups');
    }
};