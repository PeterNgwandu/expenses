<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetLevelFoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_level_fours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('level_number');
            $table->timestamps();
        });

        Schema::table('budget_level_fours', function (Blueprint $table) {
            $table->unsignedInteger('top_level_id');
            $table->foreign('top_level_id')->references('id')->on('budget_level_threes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_level_fours');
    }
}
