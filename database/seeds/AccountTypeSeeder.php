<?php

use App\Accounts\AccountType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $acc_type = new AccountType();
        $acc_type->account_type_name = 'Asset';
        $acc_type->description = 'this is asset';
        $acc_type->save();

        $acc_type = new AccountType();
        $acc_type->account_type_name = 'Liability';
        $acc_type->description = 'this is liability';
        $acc_type->save();

        $acc_type = new AccountType();
        $acc_type->account_type_name = 'Income';
        $acc_type->description = 'this is income';
        $acc_type->save();

        $acc_type = new AccountType();
        $acc_type->account_type_name = 'Expense';
        $acc_type->description = 'this is expense';
        $acc_type->save();

        $acc_type = new AccountType();
        $acc_type->account_type_name = 'Equity';
        $acc_type->description = 'this is equity';
        $acc_type->save();
    }
}
