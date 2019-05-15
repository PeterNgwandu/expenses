<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditRequisitionTemporaryTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edit_requisition_temporary_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('budget_id')->nullable()->default(0);
            $table->unsignedInteger('item_id')->nullable()->default(0);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('account_id');
            $table->string('req_no');
            $table->integer('serial_no');
            $table->string('activity_name');
            $table->string('item_name');
            $table->text('description');
            $table->string('unit_measure');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('quantity', 8, 0);
            $table->string('vat');
            $table->decimal('vat_amount', 10, 2);
            $table->decimal('gross_amount', 10, 2);
            $table->string('status');
            $table->string('post_status');
            $table->timestamps();

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
        Schema::dropIfExists('edit_requisition_temporary_tables');
    }
}
