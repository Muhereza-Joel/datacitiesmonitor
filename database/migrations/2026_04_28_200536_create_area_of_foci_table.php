<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaOfFociTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('area_of_foci', function (Blueprint $table) {
            // UUID primary key
            $table->uuid('id')->primary();

            // Foreign key to projects table
            $table->uuid('project_id');

            // Other fields
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->uuid('organisation_id');

            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('area_of_foci');
    }
}
