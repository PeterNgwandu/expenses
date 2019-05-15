<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceSupportiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_supportive_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('req_no');
            $table->integer('serial_no')->nullable();
            $table->unsignedInteger('account_id');
            $table->string('cash_collector');
            $table->string('ref_no');
            $table->decimal('amount_paid', 10, 2);
            $table->text('comment');
            $table->date('payment_date');
            $table->timestamps();

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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('finance_supportive_details');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
