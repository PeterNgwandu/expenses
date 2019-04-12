<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseRetirementTemporaryTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_retirement_temporary_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('budget_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('ret_no')->nullable();
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('expense_retirement_temporary_tables');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
