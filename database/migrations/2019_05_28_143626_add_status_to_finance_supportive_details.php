<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToFinanceSupportiveDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finance_supportive_details', function (Blueprint $table) {
            $table->string('status')->default('Pay');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finance_supportive_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
