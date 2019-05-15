<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Retirement\Retirement;
use App\StaffLevel\StaffLevel;
use App\Requisition\Requisition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\ExpenseRetirement\ExpenseRetirement;

class DashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function getAllPendingRequisitionCount($user_id)
    {
        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;

        if (Auth::user()->stafflevel_id == $normalStaff) {
            return Requisition::where('user_id', $user_id)->where('status', 'Rejected By Supervisor')->orWhere('status', 'Rejected By HOD')->orWhere('status', 'Rejected')->orWhere('status', 'Rejected By CEO')->distinct('req_no')->count('req_no');
        }elseif (Auth::user()->stafflevel_id == $supervisor) {
            return Requisition::where('status', 'onprocess')->distinct('req_no')->count('req_no');
        }elseif(Auth::user()->stafflevel_id == $hod){
            return Requisition::where('status', 'onprocess supervisor')->distinct('req_no')->count('req_no');
        }elseif(Auth::user()->stafflevel_id == $ceo){
            return Requisition::where('status', 'onprocess hod')->distinct('req_no')->count('req_no');
        }elseif(Auth::user()->stafflevel_id == $financeDirector){
            return Requisition::where('user_id', $user_id)->where('status', 'onprocess')->distinct('req_no')->count('req_no');
        }


    }

    public static function getAllRequisitionCount($user_id)
    {
        return Requisition::where('user_id', $user_id)->distinct('req_no')->count('req_no');
    }

    public static function getAllRetirementCount($user_id)
    {
        return Retirement::where('user_id', $user_id)->distinct('req_id')->distinct('ret_no')->count('ret_no');
    }

    public static function getAllExpenseRetirementCount($user_id)
    {
        return ExpenseRetirement::where('user_id', $user_id)->distinct('req_id')->distinct('ret_no')->count('ret_no');
    }

    public static function getAllPaidRequisitionCount($user_id)
    {
        return Requisition::where('user_id', $user_id)->where('status', 'Paid')->distinct('req_no')->count('req_no');
    }

    public static function getAllRetiredRequisitionCount($user_id)
    {
        return Retirement::where('user_id', $user_id)
               ->where('retirements.status','==','Retired')
               ->count();
    }
}
