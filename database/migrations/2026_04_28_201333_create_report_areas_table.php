<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportAreasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('report_areas', function (Blueprint $table) {
            // UUID primary key
            $table->uuid('id')->primary();

            // Foreign keys
            $table->uuid('project_id');
            $table->uuid('report_id');
            $table->uuid('area_of_focus_id');

            // Core fields
            $table->text('objective')->nullable();
            $table->text('activities_conducted')->nullable();
            $table->text('achievements')->nullable();
            $table->text('challenges')->nullable();
            $table->text('risks')->nullable();
            $table->text('opportunities')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('lessons_learned')->nullable();
            $table->text('stakeholder_feedback')->nullable();
            $table->text('action_plans')->nullable();

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
        Schema::dropIfExists('report_areas');
    }
}
