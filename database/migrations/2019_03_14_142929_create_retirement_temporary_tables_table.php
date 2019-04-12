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
            $table->string('req_no')->nullable();
            $table->integer('serial_no');
            $table->unsignedInteger('account_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('ret_no')->nullable(); // Retirement Number (Unique for Each Retirement)
            $table->string('supplier_id')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('item_name')->nullable();
            $table->text('description')->nullable();
            $table->string('unit_measure')->nullable();
            $table->decimal('quantity', 8, 0)->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('vat')->nullable();
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->decimal('gross_amount', 10, 2)->nullable();
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
