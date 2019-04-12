<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseRetirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_retirements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('budget_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('user_id');
            $table->string('ret_no');
            $table->string('supplier_id');
            $table->string('ref_no');
            $table->string('purchase_date');
            $table->string('item_name');
            $table->text('description');
            $table->string('unit_measure');
            $table->decimal('quantity',8, 0);
            $table->decimal('unit_price', 10, 2);
            $table->string('vat');
            $table->decimal('vat_amount', 10, 2);
            $table->decimal('gross_amount', 10, 2);
            $table->string('status');
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
        Schema::dropIfExists('expense_retirements');
    }
}
