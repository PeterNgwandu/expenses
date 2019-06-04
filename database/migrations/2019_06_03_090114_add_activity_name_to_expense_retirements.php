<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivityNameToExpenseRetirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_retirements', function (Blueprint $table) {
            $table->string('activity_name')->default('Sample Activity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_retirements', function (Blueprint $table) {
            $table->dropColumn('activity_name');
        });
    }
}
