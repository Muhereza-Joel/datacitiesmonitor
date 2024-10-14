<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('indicator_id');
            $table->decimal('current', 10, 2); // Tracks current state of the indicator for this response
            $table->decimal('progress', 10, 2); // Progress for this specific response
            $table->text('notes')->nullable();
            $table->text('lessons')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('files')->nullable();
            $table->string('status')->default('draft');
            $table->uuid('organisation_id');
            $table->uuid('user_id');
            $table->softDeletes();
            $table->timestamps();

            // Add foreign key constraint to indicator
            $table->foreign('indicator_id')->references('id')->on('indicators')->onDelete('cascade');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
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
        Schema::dropIfExists('responses');
    }
}
