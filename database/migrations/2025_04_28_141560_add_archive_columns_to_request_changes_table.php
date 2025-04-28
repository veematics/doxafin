<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchiveColumnsToRequestChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_changes', function (Blueprint $table) {
            $table->string('title')->default(false)->after('id');
            $table->boolean('is_archived')->default(false);
            $table->string('is_archived')->default(false)->nullable()->after('category');
            $table->timestamp('archived_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_changes', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_at']);
        });
    }
}