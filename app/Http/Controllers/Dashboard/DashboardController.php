<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use App\Http\Controllers\Controller;
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

    public static function getAllRequisitionCount($user_id)
    {
        return Requisition::where('user_id', $user_id)->count();
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
        return Requisition::where('user_id', $user_id)->where('status', 'Paid')->count();
    }

    public static function getAllRetiredRequisitionCount($user_id)
    {
        return Retirement::where('user_id', $user_id)
               ->where('retirements.status','==','Retired')
               ->count();
    }
}
