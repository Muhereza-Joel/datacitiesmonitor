<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocCityRegionTimeZoneOrToUserLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_action_logs', function (Blueprint $table) {
            $table->string('city')->after('country')->nullable();
            $table->string('region')->after('city')->nullable();
            $table->string('loc')->after('region')->nullable();
            $table->string('hostname')->after('ip_address')->nullable();
            $table->string('org')->after('hostname')->nullable();
            $table->string('timezone')->after('org')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_action_logs', function (Blueprint $table) {

            $table->dropColumn('city');
            $table->dropColumn('region');
            $table->dropColumn('loc');
            $table->dropColumn('hostname');
            $table->dropColumn('org');
            $table->dropColumn('timezone');
        });
    }
}
