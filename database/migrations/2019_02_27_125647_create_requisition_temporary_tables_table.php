<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionTemporaryTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition_temporary_tables', function (Blueprint $table) {
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
            $table->decimal('quantity');
            $table->string('vat');
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->decimal('gross_amount', 10, 2)->nullable();
            $table->string('status')->default('onprocess');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('requisition_temporary_tables');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
