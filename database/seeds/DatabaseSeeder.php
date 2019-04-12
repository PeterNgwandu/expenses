<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(DepartmentsSeeder::class);
        $this->call(StaffLevelSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AccountTypeSeeder::class);
        $this->call(SubAccountTypeSeeder::class);
    }
}
