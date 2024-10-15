<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_action_logs', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Use UUID as the primary key
            $table->uuid('user_id'); // Change to UUID
            $table->string('action'); // Description of the action
            $table->string('ip_address')->nullable(); // Store the user's IP address
            $table->string('resource_type'); // Type of the resource (e.g., 'Indicator')
            $table->uuid('resource_id'); // ID of the specific resource
            $table->timestamps(); // Timestamps for created_at and updated_at
            $table->softDeletes(); // Soft deletes

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_action_logs');
    }
}
