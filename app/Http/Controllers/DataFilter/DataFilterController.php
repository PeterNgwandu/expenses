<?php

namespace App\Http\Controllers\DataFilter;

use DB;
use Alert;
use Session;
use App\User;
use App\Item\Item;
use App\Limits\Limit;
use App\Budget\Budget;
use App\Accounts\Account;
use App\Comments\Comment;
use Illuminate\Http\Request;
use App\StaffLevel\StaffLevel;
use App\Department\Department;
use App\Retirement\Retirement;
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Accounts\FinanceSupportiveDetail;
use App\Temporary\RequisitionTemporaryTable;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;


class DataFilterController extends Controller
{
    // Data filter for submitted requisitions (By different staff levels)
    
    public function filterByDateSubmitted(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $Submittedrequisitions = Requisition::join('budgets','requisitions.budget_id','budgets.id')->join('items','requisitions.item_id','items.id')->join('accounts','requisitions.account_id','accounts.id')->join('users','requisitions.user_id','users.id')->join('departments','users.department_id','departments.id')->select('requisitions.*','users.*','departments.*','budgets.title as budget','items.item_name as item','accounts.account_name as account', 'users.username as username','departments.name as department','requisitions.status as status')->get();

        $user = User::where('id', Auth::user()->id)->first();
        $requisition = Requisition::where('user_id', Auth::user()->id)->first();
        $staff_levels = StaffLevel::all();
        $departments = Department::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;


        // $submitted_requisitions = Requisition::where('user_id', Auth::user()->id)->select('user_id')->distinct('user_id','created_at')->get();
        // foreach ($submitted_requisitions as $requisition) {
        if (Auth::user()->stafflevel_id == $hod)
        {

            $user_dept = User::join('departments','users.department_id','departments.id')
                              ->where('departments.id', Auth::user()->department_id)
                              ->select('users.department_id as dept_id')
                              ->distinct('dept_id')
                              ->first();

            $limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
            $limitNormalStaff = Limit::where('stafflevel_id',$normalStaff)
                                ->select('max_amount')->first();

                                $submitted_requisitions = Requisition::join('users','users.id','requisitions.user_id')
                                                          ->join('departments','users.department_id','departments.id')
                                                          ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                                          ->where('departments.id',$user_dept->dept_id)
                                                          ->where('users.stafflevel_id','!=',[$ceo])
                                                          // ->whereBetween('requisitions.gross_amount', [0,$limitSupervisor->max_amount])
                                                          ->whereIn('users.stafflevel_id',[$normalStaff, $supervisor, $hod])
                                                          ->where('requisitions.status', 'like', '%Approved%')
                                                          // ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                                          // ->orWhere('requisitions.status', 'like', '%Paid%')
                                                          ->orWhere('requisitions.status', 'like', '%onprocess%')
                                                          // ->where('requisitions.gross_amount','>',$limitNormalStaff->max_amount)
                                                          ->whereDate('requisitions.created_at', '>=', $from)
                                                          ->whereDate('requisitions.created_at', '<=', $to)
                                                          ->distinct('req_no')
                                                          ->get();


            return view('requisitions.hod-requisitions', compact('submitted_requisitions','staff_levels','requisition','Submittedrequisitions'))->withUser($user);

        }elseif (Auth::user()->stafflevel_id == $supervisor)
        {
            $user_dept = User::join('departments','users.department_id','departments.id')
                              ->where('departments.id', Auth::user()->department_id)
                              ->select('users.department_id as dept_id')
                              ->distinct('dept_id')
                              ->first();

            $limitNormalStaff = Limit::where('stafflevel_id',$normalStaff)
                                ->select('max_amount')->first();
            $limitSupervisor = Limit::where('stafflevel_id',$supervisor)
                                ->select('max_amount')->first();

            $submitted_requisitions = Requisition::join('users','users.id','requisitions.user_id')
                                      ->join('departments','users.department_id','departments.id')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                      ->where('departments.id',$user_dept->dept_id)
                                      ->where('users.stafflevel_id','!=',[$hod])
                                      // ->whereBetween('requisitions.gross_amount', [0,$limitSupervisor->max_amount])
                                      ->whereIn('users.stafflevel_id',[$normalStaff, $supervisor])
                                      ->where('requisitions.status', 'like', '%Approved%')
                                      // ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      // ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      // ->where('requisitions.gross_amount','>',$limitNormalStaff->max_amount)
                                      ->whereDate('requisitions.created_at', '>=', $from)
                                      ->whereDate('requisitions.created_at', '<=', $to)
                                      ->distinct('req_no')
                                      ->get();
            return view('requisitions.supervisor-requisitions', compact('submitted_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $ceo)
        {

            $hodLimit = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
            $ceoLimit = Limit::where('stafflevel_id',$ceo)->select('max_amount')->first();

            $submitted_requisitions = Requisition::select('user_id')
                                      ->join('users','users.id','requisitions.user_id')
                                      ->join('departments','users.department_id','departments.id')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                      ->where('requisitions.status', 'like', '%Approved%')
                                      // ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      // ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      ->whereIn('users.stafflevel_id', [$hod,$financeDirector])
                                      // ->orWhere('users.stafflevel_id', ['4','3'])
                                      // ->whereBetween('requisitions.gross_amount', ['500000','5000000'])
                                      ->whereDate('requisitions.created_at', '>=', $from)
                                      ->whereDate('requisitions.created_at', '<=', $to)
                                      ->distinct('req_no')
                                      ->get();
            return view('requisitions.ceo-requisitions', compact('submitted_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $normalStaff)
        {
            $user_dept = User::join('departments','users.department_id','departments.id')
                              ->where('departments.id', Auth::user()->department_id)
                              ->select('users.department_id as dept_id')
                              ->distinct('dept_id')
                              ->first();

            $submitted_requisitions = Requisition::join('users','users.id','requisitions.user_id')
                                      ->join('departments','users.department_id','departments.id')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                      ->where('departments.id',$user_dept->dept_id)
                                      ->where('users.stafflevel_id','!=',$hod)
                                      ->where('users.stafflevel_id','!=',$ceo)
                                      ->where('users.stafflevel_id','!=',$supervisor)
                                      ->where('users.stafflevel_id','!=',$financeDirector)
                                      ->where('requisitions.status', 'like', '%Approved%')
                                      // ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      // ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      ->whereIn('users.stafflevel_id', [$normalStaff])
                                      ->whereDate('requisitions.created_at', '>=', $from)
                                      ->whereDate('requisitions.created_at', '<=', $to)
                                      ->distinct('req_no')
                                      ->get();

            return view('requisitions.normal-staff-requisitions', compact('submitted_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $financeDirector)
        {
            $user_dept = User::join('departments','users.department_id','departments.id')
                              ->where('departments.id', Auth::user()->department_id)
                              ->select('users.department_id as dept_id')
                              ->distinct('dept_id')
                              ->first();
            $submitted_requisitions = Requisition::join('users','users.id','requisitions.user_id')
                                      ->join('staff_levels','users.stafflevel_id','staff_levels.id')
                                      ->join('departments','users.department_id','departments.id')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                      ->where('requisitions.status', 'like', '%Approved%')
                                      // ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      // ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      ->whereDate('requisitions.created_at', '>=', $from)
                                      ->whereDate('requisitions.created_at', '<=', $to)
                                      ->distinct('req_no')
                                      ->get();
            return view('requisitions.finance-requisitions', compact('submitted_requisitions','staff_levels','requisition','Submittedrequisitions'))->withUser($user);
        }else {
            $user_dept = User::join('departments','users.department_id','departments.id')
                              ->where('departments.id', Auth::user()->department_id)
                              ->select('users.department_id as dept_id')
                              ->distinct('dept_id')
                              ->first();

            $submitted_requisitions = Requisition::join('users','users.id','requisitions.user_id')
                                      ->join('staff_levels','users.stafflevel_id','staff_levels.id')
                                      ->join('departments','users.department_id','departments.id')
                                      ->where('departments.name','Finance')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                      ->where('requisitions.status', 'like', '%Approved%')
                                      // ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      // ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      ->whereDate('requisitions.created_at', '>=', $from)
                                      ->whereDate('requisitions.created_at', '<=', $to)
                                      ->distinct('req_no')
                                      ->get();
            return view('requisitions.finance-requisitions', compact('submitted_requisitions','staff_levels','requisition','Submittedrequisitions'))->withUser($user);
        }
    }

    // Data filter for approved requisitions

    public function filterByDateApproved(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        // $approved_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no, requisitions.status
        //                           FROM `requisitions`
        //                           JOIN users on requisitions.user_id = users.id
        //                           JOIN departments on users.department_id = departments.id
        //                           WHERE requisitions.status
        //                           = 'Confirmed'"));
        $approved_requisitions = DB::table('requisitions')
                                   ->join('users','requisitions.user_id','users.id')
                                   ->join('departments','users.department_id','departments.id')
                                   ->select('requisitions.*','users.username as username','departments.name as deparment')
                                   ->where('requisitions.status','Confirmed')
                                   ->whereDate('requisitions.created_at', '>=', $from)
                                   ->whereDate('requisitions.created_at', '<=', $to)
                                   ->get();
        return view('requisition.approved-requisitions', compact('approved_requisitions'));
    }

    // Data filter for submitted retirements
    public function filterByDateSubmittedRetirement(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $accounts = Account::all();
        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        $limitSupervisor = Limit::where('stafflevel_id',$supervisor)
                                ->select('max_amount')->first();
        $limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();

        if (Auth::user()->stafflevel_id == $normalStaff)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                            ->join('users','retirements.user_id','users.id')
                            ->join('departments','users.department_id','departments.id')
                            ->where('users.stafflevel_id',$normalStaff)
                            ->where('retirements.status', 'Confirmed')
                            ->orWhere('retirements.status', 'like', '%Approved%')
                            ->select('retirements.ret_no','users.username as username','departments.name as department')
                            ->whereDate('retirements.created_at', '>=', $from)
                            ->whereDate('retirements.created_at', '<=', $to)
                            ->distinct('ret_no')->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $supervisor)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                            ->join('users','retirements.user_id','users.id')
                            ->join('departments','users.department_id','departments.id')
                            ->whereIn('users.stafflevel_id',[$supervisor,$normalStaff])
                            ->where('retirements.status', 'Confirmed')
                            ->orWhere('retirements.status', 'like', '%Approved%')
                            ->whereBetween('retirements.gross_amount',[0,$limitSupervisor->max_amount])
                            ->select('retirements.ret_no','users.username as username','departments.name as department')
                            ->whereDate('retirements.created_at', '>=', $from)
                            ->whereDate('retirements.created_at', '<=', $to)
                            ->distinct('retirements.ret_no')->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $hod)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                            ->join('users','retirements.user_id','users.id')
                            ->join('departments','users.department_id','departments.id')
                            ->whereIn('users.stafflevel_id',[$normalStaff,$supervisor,$hod])
                            ->where('retirements.status', 'Confirmed')
                            ->orWhere('retirements.status', 'like', '%Approved%')
                            ->whereBetween('retirements.gross_amount',[0,$limitHOD->max_amount])
                            ->select('retirements.ret_no','users.username as username','departments.name as department')
                            ->whereDate('retirements.created_at', '>=', $from)
                            ->whereDate('retirements.created_at', '<=', $to)
                            ->distinct('ret_no')->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $ceo)
        {
            $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                            ->join('users','retirements.user_id','users.id')
                            ->join('departments','users.department_id','departments.id')
                            ->select('retirements.ret_no','users.username as username','departments.name as department')
                            ->where('retirements.status', 'Confirmed')
                            ->orWhere('retirements.status', 'like', '%Approved%')
                            ->whereIn('users.stafflevel_id',[$hod,$financeDirector])
                            ->whereDate('retirements.created_at', '>=', $from)
                            ->whereDate('retirements.created_at', '<=', $to)
                            ->distinct('ret_no')->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }
        else{

            $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                            ->join('users','retirements.user_id','users.id')
                            ->join('departments','users.department_id','departments.id')
                            ->where('retirements.status', 'Confirmed')
                            ->orWhere('retirements.status', 'like', '%Approved%')
                            ->select('retirements.ret_no','users.username as username','departments.name as department')
                            ->whereDate('retirements.created_at', '>=', $from)
                            ->whereDate('retirements.created_at', '<=', $to)
                            ->distinct('ret_no')->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }
    }
}
