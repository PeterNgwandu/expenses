<?php
use Illuminate\Support\Carbon;
use App\StaffLevel\StaffLevel;
use Illuminate\Database\Seeder;

class StaffLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staff_levels = new StaffLevel();
        $staff_levels->name = 'Head of Department (HOD)';
        $staff_levels->created_at = Carbon::now();
        $staff_levels->updated_at = Carbon::now();
        $staff_levels->save();

        $staff_levels = new StaffLevel();
        $staff_levels->name = 'Chief Executive Officer (CEO)';
        $staff_levels->created_at = Carbon::now();
        $staff_levels->updated_at = Carbon::now();
        $staff_levels->save();

        $staff_levels = new StaffLevel();
        $staff_levels->name = 'Supervisor';
        $staff_levels->created_at = Carbon::now();
        $staff_levels->updated_at = Carbon::now();
        $staff_levels->save();

        $staff_levels = new StaffLevel();
        $staff_levels->name = 'Normal Staff';
        $staff_levels->created_at = Carbon::now();
        $staff_levels->updated_at = Carbon::now();
        $staff_levels->save();

        $staff_levels = new StaffLevel();
        $staff_levels->name = 'Finance Director';
        $staff_levels->created_at = Carbon::now();
        $staff_levels->updated_at = Carbon::now();
        $staff_levels->save();
    }
}
