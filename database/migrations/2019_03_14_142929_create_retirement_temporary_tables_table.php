<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetirementTemporaryTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retirement_temporary_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('req_no');
            $table->integer('serial_no');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('user_id');
            $table->string('ret_no'); // Retirement Number (Unique for Each Retirement)
            $table->string('supplier_id');
            $table->string('ref_no');
            $table->string('purchase_date');
            $table->string('item_name');
            $table->text('description');
            $table->string('unit_measure');
            $table->decimal('quantity', 8, 0);
            $table->decimal('unit_price', 10, 2);
            $table->string('vat');
            $table->decimal('vat_amount', 10, 2);
            $table->decimal('gross_amount', 10, 2);
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retirement_temporary_tables');
    }
}
