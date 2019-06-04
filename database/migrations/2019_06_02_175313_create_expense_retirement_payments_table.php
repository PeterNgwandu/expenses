<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseRetirementPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_retirement_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ret_no');
            $table->unsignedInteger('account_id');
            $table->string('cash_collector');
            $table->string('ref_no');
            $table->decimal('amount_paid', 10, 2);
            $table->text('comment');
            $table->string('status');
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
        Schema::dropIfExists('expense_retirement_payments');
    }
}
