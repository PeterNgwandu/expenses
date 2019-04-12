<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_account_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_subtype_name');
            $table->string('description')->nullable();
            $table->unsignedInteger('account_type_id');
            $table->foreign('account_type_id')->references('id')->on('account_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_account_types');
    }
}
