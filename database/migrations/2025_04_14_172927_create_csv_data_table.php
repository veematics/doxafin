<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('csv_data', function (Blueprint $table) {
            $table->id();
            $table->string('data_name')->unique();
            $table->text('data_value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('csv_data');
    }
};