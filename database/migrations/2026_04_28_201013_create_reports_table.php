<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            // UUID primary key
            $table->uuid('id')->primary();

            // Foreign key to projects table
            $table->uuid('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            // Other fields
            $table->string('reporting_month');   // e.g. "April 2026"
            $table->string('prepared_by');       // name or identifier of preparer
            $table->string('status')->default('draft');
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
        Schema::dropIfExists('reports');
    }
}
