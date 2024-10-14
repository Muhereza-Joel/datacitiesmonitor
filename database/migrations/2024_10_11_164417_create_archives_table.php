<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');                      
            $table->text('description')->nullable();     
            $table->uuid('user_id')->nullable();      
            $table->string('status')->default('active');  
            $table->string('access_level')->default('public'); 
            $table->uuid('organisation_id')->nullable();  
            $table->timestamps();                         
            $table->softDeletes();                    

            // Foreign keys (optional based on your relationships)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archives');
    }
}
