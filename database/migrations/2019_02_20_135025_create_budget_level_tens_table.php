<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetLevelTensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_level_tens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('level_number');
            $table->timestamps();
        });

        Schema::table('budget_level_tens', function (Blueprint $table) {
            $table->unsignedInteger('top_level_id');
            $table->foreign('top_level_id')->references('id')->on('budget_level_nines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_level_tens');
    }
}
