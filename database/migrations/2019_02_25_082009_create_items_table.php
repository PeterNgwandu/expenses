<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_no');
            $table->unsignedInteger('budget_id');
            $table->unsignedInteger('account_id');
            $table->string('item_name');
            $table->text('description');
            $table->decimal('unit_price', 10, 2);
            $table->string('unit_measure');
            $table->decimal('quantity', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            $table->foreign('budget_id')->references('id')->on('budgets');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('items');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
