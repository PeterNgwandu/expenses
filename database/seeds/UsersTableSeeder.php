<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $company_id = DB::table('companies')->select('companies.id')->first();
         $dept_id = DB::table('departments')->select('departments.id')->first();

         DB::table('users')->insert([
            'username' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt(123456),
            'phone' => '0764651630',
            'phone_alternative' => '0764651630',
            'sub_acc_type_id' => '17',
            'account_no' => 'Peter-Account_No',
            'department_id' => $dept_id->id,
            'company_id' => $company_id->id,
            'stafflevel_id' => 1,
        ]);
    }
}
