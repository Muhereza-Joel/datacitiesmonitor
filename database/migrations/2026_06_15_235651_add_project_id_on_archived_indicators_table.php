<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectIdOnArchivedIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archived_indicators', function (Blueprint $table) {
            $table->uuid('project_id')->after('is_manually_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archived_indicators', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
}
