<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_no');
            $table->string('account_name');
            $table->string('description')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('sub_account_type');
            $table->foreign('sub_account_type')->references('id')->on('sub_account_types');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('accounts');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
