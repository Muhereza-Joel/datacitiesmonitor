<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceAndCountryInLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_action_logs', function (Blueprint $table) {
            $table->string('device_os')->after('resource_id')->nullable();
            $table->string('device_architecture')->after('device_os')->nullable();
            $table->string('device_browser')->after('device_architecture')->nullable();
            $table->string('country')->after('device_browser')->nullable();
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
            $table->dropColumn('device_os');
            $table->dropColumn('device_architecture');
            $table->dropColumn('device_browser');
            $table->dropColumn('country');
        });
    }
}
