<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivedIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archived_indicators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('indicator_id');
            $table->string('name')->nullable();
            $table->string('indicator_title');
            $table->text('definition')->nullable();
            $table->decimal('baseline', 10, 2)->nullable();
            $table->decimal('target', 10, 2)->nullable();
            $table->decimal('current_state', 10, 2)->nullable();
            $table->string('data_source')->nullable();
            $table->string('frequency')->nullable();
            $table->string('responsible')->nullable();
            $table->string('reporting')->nullable();
            $table->string('status')->default('archived');
            $table->string('direction')->nullable();
            $table->string('category')->default('None');
            $table->uuid('organisation_id');
            $table->uuid('archive_id');
            $table->text('qualitative_progress')->nullable();
            $table->boolean('is_manually_updated')->default(false);
            $table->uuid('theory_of_change_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('indicator_id')->references('id')->on('indicators')->onDelete('cascade');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
            $table->foreign('archive_id')->references('id')->on('archives')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archived_indicators');
    }
}
