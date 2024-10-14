<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('indicator_title');
            $table->text('definition')->nullable();
            $table->decimal('baseline', 10, 2)->nullable();
            $table->decimal('target', 10, 2)->nullable();
            $table->decimal('current_state', 10, 2)->nullable();
            $table->string('data_source')->nullable();
            $table->string('frequency')->nullable();
            $table->string('responsible')->nullable();
            $table->string('reporting')->nullable();
            $table->string('status')->default('draft');
            $table->uuid('organisation_id');
            $table->text('qualitative_progress')->nullable();
            $table->boolean('is_manually_updated')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicators');
    }
}
