<?php

use App\Accounts\SubAccountType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SubAccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sub_acc_type = new SubAccountType();
        $sub_acc_type->account_subtype_name = 'Current asset';
        $sub_acc_type->description = 'this is current asset';
        $sub_acc_type->account_type_id = 1;
        $sub_acc_type->save();

        $sub_acc_type = new SubAccountType();
        $sub_acc_type->account_subtype_name = 'Non current asset';
        $sub_acc_type->description = 'this is non current asset';
        $sub_acc_type->account_type_id = 1;
        $sub_acc_type->save();

        $sub_acc_type = new SubAccountType();
        $sub_acc_type->account_subtype_name = 'Bank Accounts';
        $sub_acc_type->description = 'this is bank accounts';
        $sub_acc_type->account_type_id = 1;
        $sub_acc_type->save();

        $sub_acc_type = new SubAccountType();
        $sub_acc_type->account_subtype_name = 'Staff Advance Accounts';
        $sub_acc_type->description = 'this is staff advance accounts';
        $sub_acc_type->account_type_id = 1;
        $sub_acc_type->save();

        $sub_acc_type = new SubAccountType();
        $sub_acc_type->account_subtype_name = 'Current liability';
        $sub_acc_type->description = 'this is current liability';
        $sub_acc_type->account_type_id = 2;
        $sub_acc_type->save();

        $sub_acc_type = new SubAccountType();
        $sub_acc_type->account_subtype_name = 'Non current liability';
        $sub_acc_type->description = 'this is non current liability';
        $sub_acc_type->account_type_id = 2;
        $sub_acc_type->save();

        $sub_acc_type = new SubAccountType();
        $sub_acc_type->account_subtype_name = 'Items income';
        $sub_acc_type->description = 'this is Items income';
        $sub_acc_type->account_type_id = 3;
        $sub_acc_type->save();

        $sub_acc_type = new SubAccountType();
        $sub_acc_type->account_subtype_name = 'Expenditure';
        $sub_acc_type->description = 'this is Expenditure';
        $sub_acc_type->account_type_id = 4;
        $sub_acc_type->save();
    }
}
