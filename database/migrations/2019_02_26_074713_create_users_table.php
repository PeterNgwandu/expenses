<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('gender')->nullable();
            $table->string('username')->nullable();
            $table->string('picture')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_alternative')->nullable();
            $table->integer('sub_acc_type_id');
            $table->string('account_no');
            $table->string('status')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('stafflevel_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('stafflevel_id')->references('id')->on('staff_levels');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('users');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
