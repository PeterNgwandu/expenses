<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivityNameToRequisitionTemporaryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisition_temporary_tables', function (Blueprint $table) {
            $table->string('activity_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisition_temporary_tables', function (Blueprint $table) {
            $table->dropColumn('activity_name');
        });
    }
}
