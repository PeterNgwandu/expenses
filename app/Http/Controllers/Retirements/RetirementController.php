<?php

namespace App\Http\Controllers\Retirements;

use DB;
use Alert;
use App\User;
use App\Limits\Limit;
use App\Comments\Comment;
use App\Accounts\Account;
use Illuminate\Http\Request;
use App\StaffLevel\StaffLevel;
use App\Retirement\Retirement;
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
use App\Comments\RetirementComment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Temporary\RetirementTemporaryTable;

class RetirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $limitCEO = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();

        if (Auth::user()->stafflevel_id == $normalStaff)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                    ->join('users','retirements.user_id','users.id')
                                    ->join('departments','users.department_id','departments.id')
                                    ->where('users.stafflevel_id',$normalStaff)
                                    ->where('retirements.status', 'Rejected By Supervisor')
                                    ->orWhere('retirements.status', 'Rejected By HOD')
                                    ->orWhere('retirements.status', 'Rejected By CEO')
                                    ->orWhere('retirements.status', 'Consult Finance')
                                    ->select('retirements.ret_no','users.username as username','departments.name as department')
                                    ->distinct('ret_no')
                                    ->get();
            return view('retirements.manage-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $supervisor)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                    ->join('users','retirements.user_id','users.id')
                                    ->join('departments','users.department_id','departments.id')
                                    ->whereIn('users.stafflevel_id',[$supervisor,$normalStaff])
                                    ->where('retirements.status', 'Retired')
                                    ->orWhere('retirements.status', 'Rejected By HOD')
                                    ->orWhere('retirements.status', 'Rejected By CEO')
                                    ->orWhere('retirements.status', 'Consult Finance')
                                    ->select('retirements.ret_no','users.username as username','departments.name as department')
                                    ->distinct('retirements.ret_no')
                                    ->get();
            return view('retirements.manage-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $hod)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                    ->join('users','retirements.user_id','users.id')
                                    ->join('departments','users.department_id','departments.id')
                                    ->whereIn('users.stafflevel_id',[$normalStaff,$supervisor,$hod])
                                    ->where('retirements.gross_amount','>=',$limitHOD->max_amount)
                                    ->where('retirements.status', 'Approved By Supervisor')
                                    ->orWhere('retirements.status', 'Retired, supervisor')
                                    ->orWhere('retirements.status', 'Rejected By CEO')
                                    ->orWhere('retirements.status', 'Consult Finance')
                                    ->select('retirements.ret_no','users.username as username','departments.name as department')
                                    ->distinct('ret_no')
                                    ->get();
            return view('retirements.manage-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $ceo)
        {
            $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                     ->join('users','retirements.user_id','users.id')
                                     ->join('departments','users.department_id','departments.id')
                                     ->select('retirements.ret_no','users.username as username','departments.name as department')
                                     ->where('retirements.status', 'Approved By Finance')
                                     ->orWhere('retirements.status', 'Retired, Finance')
                                     ->orWhere('retirements.status', 'Consult Finance')
                                     ->where('retirements.gross_amount', '>=', $limitCEO->max_amount)
                                     ->distinct('ret_no')
                                     ->get();
            return view('retirements.manage-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }
        else{

            $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                     ->join('users','retirements.user_id','users.id')
                                     ->join('departments','users.department_id','departments.id')
                                     ->where('retirements.status', 'Approved By Supervisor')
                                     ->orWhere('retirements.status', 'Approved By HOD')
                                     ->orWhere('retirements.status', 'Retired, HOD')
                                     ->orWhere('retirements.status', 'Rejected By CEO')
                                     ->orWhere('retirements.status', 'Consult Finance')
                                     ->select('retirements.ret_no','users.username as username','departments.name as department')
                                     ->distinct('ret_no')
                                     ->get();
            return view('retirements.manage-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }

    }

    public function submittedRetirements()
    {
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
                                    ->where('retirements.status', 'Retired')
                                    ->select('retirements.ret_no','users.username as username','departments.name as department')
                                    ->distinct('ret_no')
                                    ->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $supervisor)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                    ->join('users','retirements.user_id','users.id')
                                    ->join('departments','users.department_id','departments.id')
                                    ->whereIn('users.stafflevel_id',[$supervisor])
                                    ->where('retirements.status', 'Retired, supervisor')
                                    // ->whereBetween('retirements.gross_amount',[0,$limitSupervisor->max_amount])
                                    ->select('retirements.ret_no','users.username as username','departments.name as department')
                                    ->distinct('retirements.ret_no')
                                    ->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $hod)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                    ->join('users','retirements.user_id','users.id')
                                    ->join('departments','users.department_id','departments.id')
                                    ->whereIn('users.stafflevel_id',[$hod])
                                    ->where('retirements.status', 'Retired, hod')
                                    // ->orWhere('retirements.status', 'Approved By HOD')
                                    // ->orWhere('retirements.status', 'Approved By Finance')
                                    // ->whereBetween('retirements.gross_amount',[0,$limitHOD->max_amount])
                                    ->select('retirements.ret_no','users.username as username','departments.name as department')
                                    ->distinct('ret_no')
                                    ->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $ceo)
        {
            $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                     ->join('users','retirements.user_id','users.id')
                                     ->join('departments','users.department_id','departments.id')
                                     ->select('retirements.ret_no','users.username as username','departments.name as department')
                                     ->whereIn('users.stafflevel_id',[$ceo])
                                     ->where('retirements.status', 'Retired, ceo')
                                     // ->orWhere('retirements.status', 'Approved By Finance')
                                     ->distinct('ret_no')
                                     ->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }
        else{

            $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                     ->join('users','retirements.user_id','users.id')
                                     ->join('departments','users.department_id','departments.id')
                                     ->whereIn('users.stafflevel_id',[$financeDirector])
                                     ->where('retirements.status', 'Retired, ceo')
                                     ->select('retirements.ret_no','users.username as username','departments.name as department')
                                     ->distinct('ret_no')
                                     ->get();
            return view('retirements.submitted-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }
    }

    public function confirmedRetirements()
    {
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
                                    ->select('retirements.ret_no','users.username as username','departments.name as department')
                                    ->distinct('ret_no')
                                    ->get();
            return view('retirements.confirmed-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $supervisor)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                    ->join('users','retirements.user_id','users.id')
                                    ->join('departments','users.department_id','departments.id')
                                    ->whereIn('users.stafflevel_id',[$supervisor,$normalStaff])
                                    ->where('retirements.status', 'Confirmed')
                                    // ->whereBetween('retirements.gross_amount',[0,$limitSupervisor->max_amount])
                                    ->select('retirements.ret_no','users.username as username','departments.name as department')
                                    ->distinct('retirements.ret_no')
                                    ->get();
            return view('retirements.confirmed-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $hod)
        {

           $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                    ->join('users','retirements.user_id','users.id')
                                    ->join('departments','users.department_id','departments.id')
                                    ->whereIn('users.stafflevel_id',[$normalStaff,$supervisor,$hod])
                                    ->where('retirements.status', 'Confirmed')
                                    ->select('retirements.ret_no','users.username as username','departments.name as department')
                                    ->distinct('ret_no')
                                    ->get();
            return view('retirements.confirmed-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }elseif (Auth::user()->stafflevel_id == $ceo)
        {
            $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                     ->join('users','retirements.user_id','users.id')
                                     ->join('departments','users.department_id','departments.id')
                                     ->select('retirements.ret_no','users.username as username','departments.name as department')
                                     ->where('retirements.status', 'Confirmed')
                                     // ->whereIn('users.stafflevel_id',[$hod,$financeDirector])
                                     ->distinct('ret_no')
                                     ->get();
            return view('retirements.confirmed-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }
        else{

            $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                                     ->join('users','retirements.user_id','users.id')
                                     ->join('departments','users.department_id','departments.id')
                                     ->where('retirements.status', 'Confirmed')
                                     ->select('retirements.ret_no','users.username as username','departments.name as department')
                                     ->distinct('ret_no')
                                     ->get();
            return view('retirements.confirmed-retirements')->withRetirements($retirements)->withAccounts($accounts);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (RetirementController::getLatestRetNo() == null)
        {
            $ret_no = 'RET-1';
        }elseif(RetirementController::getLatestRetNo() != null) {
            //$getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            $ret_no = 'RET-'.(RetirementController::getLatestRetNoCount() + 1);
        }

        $accounts = Account::all();
        // $paid_requisitions = Requisition::where('requisitions.status','Paid')
        //                     ->where('user_id',Auth::user()->id)
        //                     ->join('budgets','requisitions.budget_id','budgets.id')
        //                     ->join('items','requisitions.item_id','items.id')
        //                     ->join('users','requisitions.user_id','users.id')
        //                     ->join('departments','users.department_id','departments.id')
        //                     ->select('requisitions.req_no','requisitions.created_at','users.username as username','departments.name as department')
        //                     ->where('budgets.status', 'Confirmed')
        //                     ->distinct('requisitions.req_no')
        //                     ->groupBy('requisitions.req_no')
        //                     ->get();

        $paid_requisitions = Requisition::where('requisitions.status','Paid')
                            ->where('user_id',Auth::user()->id)
                            // ->join('budgets','requisitions.budget_id','budgets.id')
                            // ->join('items','requisitions.item_id','items.id')
                            ->join('users','requisitions.user_id','users.id')
                            ->join('departments','users.department_id','departments.id')
                            ->select('requisitions.req_no','requisitions.created_at','users.username as username','departments.name as department')
                            // ->where('budgets.status', 'Confirmed')
                            ->distinct('requisitions.req_no')
                            ->groupBy('requisitions.req_no')
                            ->get();

        // $paid_no_budget_requsition = Requisition::where('requisitions.status','Paid')
        //                             ->where('user_id',Auth::user()->id)->where('budget_id',0)
        //                             ->join('users','requisitions.user_id','users.id')
        //                             ->join('departments','users.department_id','departments.id')
        //                             ->select('requisitions.req_no','users.username as username','departments.name as department')
        //                             ->distinct('requisitions.req_no')->get();

        return view('retirements.create-retirements', compact('paid_requisitions'))->withAccounts($accounts);
    }

    public static function getLatestRetNoCount()
    {
        return Retirement::select('ret_no')->latest()->distinct('ret_no')->count('ret_no');
    }

    public static function getLatestRetNo()
    {
        return Retirement::select('ret_no')->latest()->first();
    }

    public static function getTheLatestRetirementNumber()
    {
        if (RetirementController::getLatestRetNo() == null)
        {
            $ret_no = 'RET-1';
        }elseif(RetirementController::getLatestRetNo() != null) {
            //$getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            $ret_no = 'RET-'.(RetirementController::getLatestRetNoCount() + 1);
        }

        return $ret_no;
    }

    public function submit_retire_row(Request $request)
    {

        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;



        if(Auth::user()->stafflevel_id == $normalStaff){

            $status = 'Retired';
        }elseif(Auth::user()->stafflevel_id == $supervisor){
            $status = 'Retired, supervisor';
        }elseif(Auth::user()->stafflevel_id == $hod){
            $status = 'Retired, hod';
        }elseif(Auth::user()->stafflevel_id == $ceo){
            $status = 'Retired, ceo';
        }elseif(Auth::user()->stafflevel_id == $financeDirector){
            $status = 'Retired, finance';
        }

        $request->status = 'Retired';
        if ($request->vat == 'VAT Inclusive')
        {
            $vat_amount = (($request->quantity * $request->unit_price / 1.18) * 0.18);
            $gross_amount = ($request->quantity * $request->unit_price);
        }elseif($request->vat == 'VAT Exclusive')
        {
            $vat_amount = (($request->quantity * $request->unit_price * 0.18));
            $gross_amount = ($request->quantity * $request->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($request->quantity * $request->unit_price);
        }

        if (Retirement::select('ret_no')->latest()->first() == null)
        {
            $ret_no = 'RET-1';
        }elseif(Retirement::select('ret_no')->latest()->first() != null) {
            $getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            $ret_no = 'RET-'.($getLatestRetNo + 1);
        }

        DB::table('retirement_temporary_tables')->insert(['req_no' => $request->req_no, 'serial_no' => $request->serial_no, 'account_id' => $request->account_id, 'user_id' => $request->user_id, 'ret_no' => $request->ret_no, 'supplier_id' => $request->supplier_id, 'ref_no' => $request->ref_no, 'item_name' => $request->item_name2, 'purchase_date' => $request->purchase_date, 'unit_measure' => $request->unit_measure, 'quantity' => $request->quantity, 'unit_price' => $request->unit_price,
            'vat' => $request->vat, 'description' => $request->description, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount, 'status' => $status, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);



            $data = DB::table('retirement_temporary_tables')
                        ->join('requisitions','retirement_temporary_tables.req_no','requisitions.req_no')
                        ->join('budgets','requisitions.budget_id','budgets.id')
                        ->join('items','requisitions.item_id','items.id')
                        ->join('accounts','requisitions.account_id','accounts.id')
                        ->select('retirement_temporary_tables.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')
                        ->where('requisitions.serial_no', $request->serial_no)
                        ->where('budgets.status', 'Confirmed')
                        ->groupBy('retirement_temporary_tables.ret_no')
                        ->get();

            $data_no_budget = DB::table('retirement_temporary_tables')->join('requisitions','retirement_temporary_tables.req_no','requisitions.req_no')->join('accounts','requisitions.account_id','accounts.id')->select('retirement_temporary_tables.*','accounts.account_name as account')->groupBy('retirement_temporary_tables.ret_no')->get();

            $view = view('retirements.render-retired-items', compact('data_no_budget'))->with('data', $data)->render();




        return response()->json(['result' => $view]);
    }

    public function add_retirement_row(Request $request)
    {

        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;



        if(Auth::user()->stafflevel_id == $normalStaff){

            $status = 'Retired';
        }elseif(Auth::user()->stafflevel_id == $supervisor){
            $status = 'Retired, supervisor';
        }elseif(Auth::user()->stafflevel_id == $hod){
            $status = 'Retired, hod';
        }elseif(Auth::user()->stafflevel_id == $ceo){
            $status = 'Retired, ceo';
        }elseif(Auth::user()->stafflevel_id == $financeDirector){
            $status = 'Retired, finance';
        }

        // $request->status = 'Retired';
        if ($request->vat == 'VAT Inclusive')
        {
            $vat_amount = (($request->quantity * $request->unit_price / 1.18) * 0.18);
            $gross_amount = ($request->quantity * $request->unit_price);
        }elseif($request->vat == 'VAT Exclusive')
        {
            $vat_amount = (($request->quantity * $request->unit_price * 0.18));
            $gross_amount = ($request->quantity * $request->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($request->quantity * $request->unit_price);
        }

        // if (Retirement::select('ret_no')->latest()->first() == null)
        // {
        //     $ret_no = 'RET-1';
        // }elseif(Retirement::select('ret_no')->latest()->first() != null) {
        //     $getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
        //     $ret_no = 'RET-'.($getLatestRetNo + 1);
        // }

        DB::table('retirement_temporary_tables')->insert(['req_no' => $request->req_no, 'serial_no' => $request->serial_no, 'account_id' => $request->account_id, 'user_id' => $request->user_id, 'ret_no' => $request->ret_no, 'supplier_id' => $request->supplier_id, 'ref_no' => $request->ref_no, 'item_name' => $request->item_name2, 'purchase_date' => $request->purchase_date, 'unit_measure' => $request->unit_measure, 'quantity' => $request->quantity, 'unit_price' => $request->unit_price,
            'vat' => $request->vat, 'description' => $request->description, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount, 'status' => $status, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);



            $data = DB::table('retirement_temporary_tables')
                        ->join('requisitions','retirement_temporary_tables.req_no','requisitions.req_no')
                        ->join('budgets','requisitions.budget_id','budgets.id')
                        ->join('items','requisitions.item_id','items.id')
                        ->join('accounts','requisitions.account_id','accounts.id')
                        ->select('retirement_temporary_tables.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')
                        ->where('requisitions.serial_no', $request->serial_no)
                        ->where('budgets.status', 'Confirmed')
                        ->groupBy('retirement_temporary_tables.ret_no')
                        ->get();

            $data_no_budget = DB::table('retirement_temporary_tables')->join('requisitions','retirement_temporary_tables.req_no','requisitions.req_no')->join('accounts','requisitions.account_id','accounts.id')->select('retirement_temporary_tables.*','accounts.account_name as account')->groupBy('retirement_temporary_tables.ret_no')->get();

            $view = view('retirements.render-retired-items', compact('data_no_budget'))->with('data', $data)->render();




        return response()->json(['result' => $view]);
    }

    public function submit_edit_retire_row(Request $request)
    {

        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;



        if(Auth::user()->stafflevel_id == $normalStaff){

            $status = 'Retired';
        }elseif(Auth::user()->stafflevel_id == $supervisor){
            $status = 'Retired, supervisor';
        }elseif(Auth::user()->stafflevel_id == $hod){
            $status = 'Retired, hod';
        }elseif(Auth::user()->stafflevel_id == $ceo){
            $status = 'Retired, ceo';
        }elseif(Auth::user()->stafflevel_id == $financeDirector){
            $status = 'Retired, finance';
        }

        $request->status = 'Retired';
        if ($request->vat == 'VAT Inclusive')
        {
            $vat_amount = (($request->quantity * $request->unit_price / 1.18) * 0.18);
            $gross_amount = ($request->quantity * $request->unit_price);
        }elseif($request->vat == 'VAT Exclusive')
        {
            $vat_amount = (($request->quantity * $request->unit_price * 0.18));
            $gross_amount = ($request->quantity * $request->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($request->quantity * $request->unit_price);
        }

        if (Retirement::select('ret_no')->latest()->first() == null)
        {
            $ret_no = 'RET-1';
        }elseif(Retirement::select('ret_no')->latest()->first() != null) {
            $getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            $ret_no = 'RET-'.($getLatestRetNo + 1);
        }

        DB::table('retirement_temporary_tables')->insert(['req_no' => $request->req_no, 'serial_no' => $request->serial_no, 'account_id' => $request->account_id, 'user_id' => $request->user_id, 'ret_no' => $request->ret_no, 'supplier_id' => $request->supplier_id, 'ref_no' => $request->ref_no, 'item_name' => $request->item_name2, 'purchase_date' => $request->purchase_date, 'unit_measure' => $request->unit_measure, 'quantity' => $request->quantity, 'unit_price' => $request->unit_price,
            'vat' => $request->vat, 'description' => $request->description, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount, 'status' => $status, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);



            // $data = DB::table('retirement_temporary_tables')
            //             ->join('requisitions','retirement_temporary_tables.req_no','requisitions.req_no')
            //             ->join('budgets','requisitions.budget_id','budgets.id')
            //             ->join('items','requisitions.item_id','items.id')
            //             ->join('accounts','requisitions.account_id','accounts.id')
            //             ->select('retirement_temporary_tables.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')
            //             ->where('requisitions.serial_no', $request->serial_no)
            //             ->where('budgets.status', 'Confirmed')
            //             ->groupBy('retirement_temporary_tables.ret_no')
            //             ->where('ret_no', $request->req_no)
            //             ->latest()
            //             ->first();

            $data_no_budget = DB::table('retirement_temporary_tables')
                                ->join('requisitions','retirement_temporary_tables.req_no','requisitions.req_no')
                                ->join('accounts','requisitions.account_id','accounts.id')
                                ->select('retirement_temporary_tables.*','accounts.account_name as account','requisitions.activity_name')
                                ->where('ret_no', $request->ret_no)
                                // ->groupBy('retirement_temporary_tables.ret_no')
                                ->distinct()
                                ->get();

            // $view = view('retirements.render-edit-retired-items', compact('data_no_budget'))->with('data', $data)->render();
            $view = view('retirements.render-edit-retired-items', compact('data_no_budget'))->render();




        return response()->json(['result' => $view]);
    }

    public function permanentRetirementSubmission($retire_no)
    {
        $retirements = RetirementTemporaryTable::where('ret_no', $retire_no)
                       ->where('user_id', Auth::user()->id)->get();

        foreach ($retirements as $retirement) {
            DB::table('retirements')->insert([
                'req_no' => $retirement->req_no,
                'serial_no' => $retirement->serial_no,
                'account_id' => $retirement->account_id,
                'user_id' => $retirement->user_id,
                'ret_no' => $retirement->ret_no,
                'supplier_id' => $retirement->supplier_id,
                'ref_no' => $retirement->ref_no,
                'item_name' => $retirement->item_name,
                'purchase_date' => $retirement->purchase_date,
                'unit_measure' => $retirement->unit_measure,
                'unit_price' => $retirement->unit_price,
                'quantity' => $retirement->quantity,
                'vat' => $retirement->vat,
                'description' => $retirement->description,
                'vat_amount' => $retirement->vat_amount,
                'gross_amount' => $retirement->gross_amount,
                'status' =>$retirement->status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'approver_id' => null,
            ]);
            // Requisition::where('req_no', $retirement->req_no)->update(['requisitions.status' => $retirement->status]);
        }

        RetirementTemporaryTable::truncate();

        session()->flash('message', 'Retirement has being created');
        return redirect(url('/submitted-retirements'))->with('message');
    }

    public function editRetirement($ret_no)
    {





        $accounts = Account::all();
        // $retirements = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
        //                          ->join('budgets','requisitions.budget_id','budgets.id')
        //                          ->join('items','requisitions.item_id','items.id')
        //                          ->join('accounts','retirements.account_id','accounts.id')
        //                          ->select('retirements.*','budgets.title as budget','items.item_name as item','accounts.account_name as account_id','requisitions.activity_name')
        //                          ->where('ret_no', $ret_no)
        //                          ->distinct()
        //                          ->get();

       $retirements_no_budget = RetirementTemporaryTable::join('requisitions','retirement_temporary_tables.req_no','requisitions.req_no')
                                ->join('accounts','retirement_temporary_tables.account_id','accounts.id')
                                ->select('retirement_temporary_tables.*','accounts.account_name as account_id','requisitions.activity_name')
                                ->where('ret_no', $ret_no)
                                ->distinct()
                                // ->groupBy('retirement_temporary_tables.created_at')
                                ->get();

        $associated_ReqNo = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')->select('requisitions.req_no')->first();
        $requisition = Retirement::where('req_no', $associated_ReqNo->req_no)->get();
        $submitted_requisitions = Requisition::where('req_no', $associated_ReqNo->req_no)
                                  ->where('requisitions.status', 'Paid')
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->join('items','requisitions.item_id','items.id')
                                  ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                                  ->where('budgets.status', 'Confirmed')
                                  ->get();

        $submitted_paid_no_budget = Requisition::where('requisitions.status','Paid')->where('user_id',Auth::user()->id)->where('req_no', $associated_ReqNo->req_no)->where('budget_id',0)->get();

        return view('retirements.edit-retirement', compact('submitted_requisitions','req_no','ret_no','submitted_paid_no_budget','retirements','retirements_no_budget'))->withAccounts($accounts)->withRequisition($requisition);

    }

    public function sendRetirement($ret_no)
    {
        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;

        if(Auth::user()->stafflevel_id == $normalStaff){

            $status = 'Retired';
        }elseif(Auth::user()->stafflevel_id == $supervisor){
            $status = 'Retired, supervisor';
        }elseif(Auth::user()->stafflevel_id == $hod){
            $status = 'Retired, hod';
        }elseif(Auth::user()->stafflevel_id == $ceo){
            $status = 'Retired, ceo';
        }elseif(Auth::user()->stafflevel_id == $financeDirector){
            $status = 'Retired, finance';
        }

        $retirements = Retirement::where('ret_no', $ret_no)->where('user_id', Auth::user()->id)->where('status', '!=', 'Edited')->distinct()->get();

        foreach($retirements as $retirement)
        {
            $data = new RetirementTemporaryTable();
            $data->id = $retirement->id;
            $data->req_no = $retirement->req_no;
            $data->serial_no = $retirement->serial_no;
            $data->account_id = $retirement->account_id;
            $data->user_id = $retirement->user_id;
            $data->ret_no = $retirement->ret_no;
            $data->supplier_id = $retirement->supplier_id;
            $data->ref_no = $retirement->ref_no;
            $data->purchase_date = $retirement->purchase_date;
            $data->item_name = $retirement->item_name;
            $data->description = $retirement->description;
            $data->unit_measure = $retirement->unit_measure;
            $data->quantity = $retirement->quantity;
            $data->unit_price = $retirement->unit_price;
            $data->vat = $retirement->vat;
            $data->vat_amount = $retirement->vat_amount;
            $data->gross_amount = $retirement->gross_amount;
            $data->status = $status;
            $data->created_at = Carbon::now();
            $data->updated_at = Carbon::now();
            $data->save();
        }
    }

    public function truncateRetirementLines($user_id, $ret_no)
    {
        foreach(RetirementTemporaryTable::where('user_id', $user_id)->where('ret_no', $ret_no)->get() as $retirement)
        {
            $result = $retirement->truncate();
        }
        return response()->json(['result' => $result]);
    }

    public function updateSupplier($supplier, $data_id)
    {
        $result = RetirementTemporaryTable::where('id', $data_id)->update([
            'supplier_id' => $supplier,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateRefNo($ref_no, $data_id)
    {
        $result = RetirementTemporaryTable::where('id', $data_id)->update([
            'ref_no' => $ref_no,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateItemName($item_name, $data_id)
    {
        $result = RetirementTemporaryTable::where('id', $data_id)->update([
            'item_name' => $item_name,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateUnitMeasure($unit_measure, $data_id)
    {
        $result = RetirementTemporaryTable::where('id', $data_id)->update([
            'unit_measure' => $unit_measure,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateQuantity($quantity, $data_id)
    {
        $result = RetirementTemporaryTable::where('id', $data_id)->update([
            'quantity' => $quantity,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateUnitPrice($unit_price, $data_id)
    {
        $result = RetirementTemporaryTable::where('id', $data_id)->update([
            'unit_price' => $unit_price,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateDescription($description, $data_id)
    {
        $result = RetirementTemporaryTable::where('id', $data_id)->update([
            'description' => $description,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateRetirement($user_id, $ret_no)
    {
        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        Retirement::where('user_id', $user_id)->where('ret_no', $ret_no)->update(['status' => 'Edited']);
        $data = RetirementTemporaryTable::where('user_id', $user_id)->where('ret_no', $ret_no)->get();

        foreach($data as $data)
        {

            $retirement = new Retirement();
            $retirement->req_no = $data->req_no;
            $retirement->serial_no = $data->serial_no;
            $retirement->account_id = $data->account_id;
            $retirement->user_id = $data->user_id;
            $retirement->ret_no = $data->ret_no;
            $retirement->supplier_id = $data->supplier_id;
            $retirement->ref_no = $data->ref_no;
            $retirement->purchase_date = $data->purchase_date;
            $retirement->item_name = $data->item_name;
            $retirement->description = $data->description;
            $retirement->unit_measure = $data->unit_measure;
            $retirement->quantity = $data->quantity;
            $retirement->unit_price = $data->unit_price;
            $retirement->vat = $data->vat;
            if ($data->vat == 'VAT Inclusive')
            {
                $vat_amount = (($data->quantity * $data->unit_price / 1.18) * 0.18);
                $gross_amount = ($data->quantity * $data->unit_price);
            }elseif($data->vat == 'VAT Exclusive')
            {
                $vat_amount = (($data->quantity * $data->unit_price * 0.18));
                $gross_amount = ($data->quantity * $data->unit_price * 1.18);
            }else
            {
                $vat_amount = 0;
                $gross_amount = ($data->quantity * $data->unit_price);
            }
            $retirement->vat_amount = $vat_amount;
            $retirement->gross_amount = $gross_amount;
            $retirement->created_at = $data->created_at;
            $retirement->updated_at = $data->updated_at;

            if(Auth::user()->stafflevel_id == $normalStaff)
            {
                $retirement->status = 'Retired';
            }elseif(Auth::user()->stafflevel_id == $supervisor)
            {
                $retirement->status = 'Retired, supervisor';
            }elseif(Auth::user()->stafflevel_id == $hod)
            {
                $retirement->status = 'Retired, hod';
            }elseif(Auth::user()->stafflevel_id == $ceo)
            {
                $retirement->status = 'Retired, ceo';
            }elseif(Auth::user()->stafflevel_id == $financeDirector)
            {
                $retirement->status = 'Retired, finance';
            }
            $retirement->save();

        }
        RetirementTemporaryTable::truncate();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response()->json(['result' => Input::all()]);
        if (Retirement::select('ret_no')->latest()->first() == null)
        {
            $ret_no = 'RET-1';
        }elseif(Retirement::select('ret_no')->latest()->first() != null) {
            $getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            $ret_no = 'RET-'.($getLatestRetNo + 1);
        }


        $result = Input::all();
        $retirement = new Retirement();
        for($count=0; $count < count($result); $count++)
        {
             $request->status = 'Retired';
            // $vatAmountInclusive = (($result[$count]['quantity'] * $result[$count]['unit_price'] / 1.18) * 0.18);
            // $vatAmountExclusive[$count] = (($request->quantity[$count] * $request->unit_price[$count] * 0.18));
            // $vatAmountNonVat = 0;

            // $grossAmountNovVat = ($request->quantity[$count] * $request->unit_price[$count]);
            // $grossAmountVatInclusive[$count] = ($request->quantity[$count] * $request->unit_price[$count]);
            // $grossAmountVatExclusive[$count] = ($request->quantity[$count] * $request->unit_price[$count] * 1.18);

            // if ($request->vat == 'VAT Exclusive') {
            //     $retirementVat = $retirement->vat_amount;
            //     $retirementGrossAmount = $retirement->gross_amount;
            //     $retirementVat[$count] = $vatAmountExclusive;
            //     $retirementGrossAmount[$count] = $grossAmountVatExclusive;
            // }elseif ($request->vat == 'VAT Inclusive') {
            //     $retirementVat[$count] = $vatAmountInclusive;
            //     $retirementGrossAmount[$count] = $grossAmountVatInclusive;

            // }else{
            //    $retirementVat[$count] = $vatAmountNonVat;
            //    $retirementGrossAmount[$count] = $grossAmountNovVat;
            // }

            $data = array(
                'req_id' => $request->req_id[0],
                'budget_id' => $request->budget_id[0],
                'item_id' => $request->item_id[0],
                'user_id' => Auth::user()->id,
                'ret_no' => $ret_no[0],
                'supplier_id' => $request->supplier_id[0],
                'ref_no' => $request->ref_no[0],
                'purchase_date' => $request->purchase_date[0],
                'item_name' => $request->item_name[0],
                'unit_measure' => $request->unit_measure[0],
                'quantity' => $request->quantity[0],
                'unit_price' => $request->unit_price[0],
                'vat' => $request->vat[0],
                'account_id' => $request->account_id[0],
                'description' => $request->description[0],
                'vat_amount' => 0,
                'gross_amount' => 0,
                'status' => $request->status[0],

            );

            // return json_encode($data);

            $retirement->insert($data);
        }
            return response()->json(['result' => $data]);
            return redirect()->route('retirements.index')->with('message');

        // $data = Input::all();

        // dump($data);
        //return json_decode($data);
        // return response()->json(['result' => $data]);

        // foreach ($data as $data) {
        //     if (Retirement::select('ret_no')->latest()->first() == null) {
        //         $ret_no = 'RET-1';
        //     }elseif(Retirement::select('ret_no')->latest()->first() != null) {
        //         $getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
        //         $ret_no = 'RET-'.($getLatestRetNo + 1);
        //     }

        //     $vatAmountInclusive = (($request->quantity * $request->unit_price / 1.18) * 0.18);
        //     $vatAmountExclusive = (($request->quantity * $request->unit_price * 0.18));
        //     $vatAmountNonVat = 0;

        //     $grossAmountNovVat = ($request->quantity * $request->unit_price);
        //     $grossAmountVatInclusive = ($request->quantity * $request->unit_price);
        //     $grossAmountVatExclusive = ($request->quantity * $request->unit_price * 1.18);

        //     $retirement = new Retirement();
            // foreach ($request->supplier_id as $key => $value) {
            //     $data = array(
            //         'supplier_id' => $request->supplier_id[$key],
            //         'ref_no' => $request->ref_no[$key],
            //     );
            // //$retirement->supplier_id = $request->supplier_id;
            // }
            // dd($data);
            // $retirement->supplier_id = $data->supplier_id;
            // $retirement->ref_no = $data->ref_no;
            // $retirement->purchase_date = $data->purchase_date;
            // $retirement->req_id = $data->req_id;
            // $retirement->budget_id = $data->budget_id;
            // $retirement->item_id = $data->item_id;
            // $retirement->account_id = $data->account_id;
            // $retirement->user_id = Auth::user()->id;
            // $retirement->ret_no = $ret_no;
            // $retirement->item_name = $data->item_name;
            // $retirement->description = $data->description;
            // $retirement->unit_measure = $data->unit_measure;
            // $retirement->unit_price = $data->unit_price;
            // $retirement->quantity = $data->quantity;
            // $retirement->vat = $data->vat;

            // if ($data->vat == 'VAT Exclusive') {
            //     $retirement->vat_amount = $vatAmountExclusive;
            //     $retirement->gross_amount = $grossAmountVatExclusive;
            // }elseif ($data->vat == 'VAT Inclusive') {
            //     $retirement->vat_amount = $vatAmountInclusive;
            //     $retirement->gross_amount = $grossAmountVatInclusive;

            // }else{
            //    $retirement->vat_amount = $vatAmountNonVat;
            //    $retirement->gross_amount = $grossAmountNovVat;
            // }
            // $retirement->status = 'Retired';
            //dd($retirement);
        //     $retirement->save();
        // }


        //$retirement->save();

        // session()->flash('message', 'Retirement has being created');
        // return redirect()->route('retirements.index')->with('message');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $retirement_comments = RetirementComment::where('ret_id',$id)->first();
        $retirement = Retirement::findOrFail($id);
        $user = User::join('retirements','retirements.user_id','users.id')->where('retirements.id',$id)->first();
        return view('retirements.view-retirements',compact('retirement_comments'))->withRetirement($retirement)->withUser($user);
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
        $retirement = Retirement::findOrFail($id);

        $vatAmountInclusive = (($request->quantity * $request->unit_price / 1.18) * 0.18);
        $vatAmountExclusive = (($request->quantity * $request->unit_price * 0.18));
        $vatAmountNonVat = 0;

        $grossAmountNovVat = ($request->quantity * $request->unit_price);
        $grossAmountVatInclusive = ($request->quantity * $request->unit_price);
        $grossAmountVatExclusive = ($request->quantity * $request->unit_price * 1.18);

        $retirement->req_id = $request->req_id;
        $retirement->budget_id = $request->budget_id;
        $retirement->item_id = $request->item_id;
        $retirement->account_id = $request->account_id;
        $retirement->user_id = Auth::user()->id;
        $retirement->ret_no = $request->ret_no;
        $retirement->item_name = $request->item_name;
        $retirement->description = $request->description;
        $retirement->unit_measure = $request->unit_measure;
        $retirement->unit_price = $request->unit_price;
        $retirement->quantity = $request->quantity;
        $retirement->vat = $request->vat;

        if ($request->vat == 'VAT Exclusive') {
            $retirement->vat_amount = $vatAmountExclusive;
            $retirement->gross_amount = $grossAmountVatExclusive;
        }elseif ($request->vat == 'VAT Inclusive') {
            $retirement->vat_amount = $vatAmountInclusive;
            $retirement->gross_amount = $grossAmountVatInclusive;

        }else{
           $retirement->vat_amount = $vatAmountNonVat;
           $retirement->gross_amount = $grossAmountNovVat;
        }
        $retirement->status = 'Retired';
        //dd($retirement);
        $retirement->save();

        session()->flash('message', 'Retirement has being created');
        return redirect()->route('retirements.index')->with('message');

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

    public function approveRetirement($ret_no, $user_id)
    {
        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        $limitSupervisor = Limit::where('stafflevel_id',$supervisor)
                                ->select('max_amount')->first();
        $limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
        $limitCEO = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
        $amount_finance_can_approve = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
        $retirement_status = Retirement::where('ret_no', $ret_no)->select('status')->first();

        $requisitionTotal = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                            ->where('retirements.ret_no',$ret_no)
                            ->sum('requisitions.gross_amount');

        $retirementTotal = Retirement::where('ret_no',$ret_no)->sum('retirements.gross_amount');
        $user_staff_level = Retirement::join('users','retirements.user_id','users.id')->join('staff_levels','users.stafflevel_id','staff_levels.id')->select('users.stafflevel_id')->where('ret_no', $ret_no)->first();

        if (Auth::user()->stafflevel_id == 1)
        {
            $result = DB::table('retirements')->where('ret_no',$ret_no)->where('status','!=','Edited')->update([
                'status' => 'Approved By HOD',
                'approver_id' => $user_id,
            ]);
            alert()->success('Retirement approved successfuly', 'Good Job');
            return redirect(url('retirements'));
        }elseif (Auth::user()->stafflevel_id == 2)
        {
            $result = DB::table('retirements')->where('ret_no',$ret_no)->where('status','!=','Edited')->update([
                'status' => 'Confirmed',
                'approver_id' => $user_id,
            ]);
            alert()->success('Retirement approved successfuly', 'Good Job');
            return redirect(url('retirements'));
        }elseif (Auth::user()->stafflevel_id == 3)
        {
            $result = DB::table('retirements')->where('ret_no',$ret_no)->where('status','!=','Edited')->update([
                'status' => 'Approved By Supervisor',
                'approver_id' => $user_id,
                'approver_id' => $user_id,
            ]);
            alert()->success('Retirement approved successfuly', 'Good Job');
            return redirect(url('retirements'));
        }elseif (Auth::user()->stafflevel_id == 5 && $retirementTotal >= $limitCEO->max_amount || $user_staff_level->stafflevel_id == $hod)
        {
                if($retirementTotal > $amount_finance_can_approve->max_amount && $retirement_status->status == 'Approved By Supervisor')
                {
                    alert()->error('Wait for HOD Approval')->persistent('close');
                    return redirect()->back();
                }
                $result = DB::table('retirements')->where('ret_no',$ret_no)->where('status','!=','Edited')->update([
                    'status' => 'Approved By Finance',
                    'approver_id' => $user_id,
                ]);


            alert()->success('Retirement approved successfuly', 'Good Job');
            return redirect(url('retirements'));
        }else
        {
            $result = DB::table('retirements')->where('ret_no',$ret_no)->where('status','!=','Edited')->update([
                'status' => 'Confirmed',
                'approver_id' => $user_id,
            ]);

            alert()->success('Retirement approved successfuly', 'Good Job');
            return redirect(url('retirements'));
        }
    }

    public function rejectRetirement($ret_no)
    {
        if (Auth::user()->stafflevel_id == 1)
        {
            $result = DB::table('retirements')->where('ret_no',$ret_no)->where('status','!=','Edited')->update([
                'status' => 'Rejected By HOD',
            ]);
            return view('retirements.retirement-comments', compact('ret_no'));
        }elseif (Auth::user()->stafflevel_id == 2)
        {
            $result = DB::table('retirements')->where('ret_no',$ret_no)->where('status','!=','Edited')->update([
                'status' => 'Rejected By CEO',
            ]);
            return view('retirements.retirement-comments', compact('ret_no'));
        }elseif (Auth::user()->stafflevel_id == 3)
        {
            $result = DB::table('retirements')->where('ret_no',$ret_no)->where('status','!=','Edited')->update([
                'status' => 'Rejected By Supervisor',
            ]);
            return view('retirements.retirement-comments', compact('ret_no'));
        }elseif (Auth::user()->stafflevel_id == 5)
        {
            $result = DB::table('retirements')->where('ret_no',$ret_no)->where('status','!=','Edited')->update([
                'status' => 'Consult Finance',
            ]);
            return view('retirements.retirement-comments', compact('ret_no'));
        }
    }


    // Handling Temporary Table

    public function getSupplier(Request $request, $req_id, $budget, $item, $account, $supplier)
    {
        $data = RetirementTemporaryTable::where('req_id', $req_id)
                ->insert([
                    'req_id' => $req_id,
                    'budget_id' => $budget,
                    'item_id' => $item,
                    'account_id' => $account,
                    'user_id' => Auth::user()->id,
                    'supplier_id' => $supplier,
                ]);
        return response()->json(['result' => $data]);
    }

    public function getRefNo(Request $request, $req_id, $ref_no)
    {
        $data = RetirementTemporaryTable::where('req_id', $req_id)
                ->update([
                    'ref_no' => $ref_no,
                ]);
        return response()->json(['result' => $data]);
    }

    public static function getRetirementTotal($ret_no)
    {
        return Retirement::where('ret_no',$ret_no)->where('status', '!=', 'Edited')->sum('gross_amount');
    }

    public function getRetirementForm($req_no)
    {
        $accounts = Account::all();
        $requisition = Requisition::where('req_no', $req_no)->get();
        $submitted_requisitions = Requisition::where('req_no', $req_no)
                                  ->where('requisitions.status', 'Paid')
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->join('items','requisitions.item_id','items.id')
                                  ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                                  ->where('budgets.status', 'Confirmed')
                                  ->get();

        $submitted_paid_no_budget = Requisition::where('requisitions.status','Paid')->where('user_id',Auth::user()->id)->where('req_no', $req_no)->where('budget_id',0)->get();

        return view('retirements.retire', compact('submitted_requisitions','req_no','submitted_paid_no_budget'))->withAccounts($accounts)->withRequisition($requisition);
    }

    public function addRetirement($ret_no,$req_no)
    {
        $accounts = Account::all();
        $requisition = Requisition::where('req_no', $req_no)->get();
        $submitted_requisitions = Requisition::where('req_no', $req_no)
                                  ->where('requisitions.status', 'Paid')
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->join('items','requisitions.item_id','items.id')
                                  ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                                  ->where('budgets.status', 'Confirmed')
                                  ->get();

        $submitted_paid_no_budget = Requisition::where('requisitions.status','Paid')->where('user_id',Auth::user()->id)->where('req_no', $req_no)->where('budget_id',0)->get();

        return view('retirements.add-retirement', compact('submitted_requisitions','req_no','ret_no','submitted_paid_no_budget'))->withAccounts($accounts)->withRequisition($requisition);
    }

    public function getAllRetirement($ret_no)
    {
        $accounts = Account::all();
        $submitted_requisitions = Requisition::where('requisitions.status', 'Paid')
                          ->join('budgets','requisitions.budget_id','budgets.id')
                          ->join('items','requisitions.item_id','items.id')
                          ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                          ->where('budgets.status', 'Confirmed')
                          ->get();
        $retirements = Retirement::where('ret_no', $ret_no)->where('status', '!=', 'Edited')->get();
        $retirement = Retirement::where('ret_no', $ret_no)->where('status', '!=', 'Edited')->first();
        $retirement_status = Retirement::where('ret_no', $ret_no)->where('status', '!=', 'Edited')->select('retirements.status')->first();
        $requisition_no = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')->select('retirements.req_no')->where('retirements.ret_no', $ret_no)->first();

        $comments = RetirementComment::where('ret_no', $ret_no)->get();
        return view('retirements.new-all-retirements', compact('submitted_requisitions','retirements','comments','ret_no','retirement','requisition_no', 'retirement_status'))->withAccounts($accounts);
    }

    // public function getAllPaidRequisition($req_no)
    // {
    //     $submitted_requisitions = Requisition::where('req_no', $req_no)
    //                               ->where('requisitions.status', 'Paid')
    //                               ->join('budgets','requisitions.budget_id','budgets.id')
    //                               ->join('items','requisitions.item_id','items.id')
    //                               ->select('requisitions.*','budgets.title as budget','items.item_name as item')
    //                               ->get();
    //     $retirements = Retirement::where('req_no', $req_no)->get();
    //     return view('retirements.all-retirements', compact('submitted_requisitions','retirements'));
    // }

    public static function getRequestedAmount($req_no, $serial_no)
    {
        $amount_requested =  Requisition::where('req_no', $req_no)->where('serial_no', $serial_no)
                            ->where('status','!=','Deleted')->where('status','!=','Edited')
                            ->select('requisitions.gross_amount')->first();
        return $amount_requested->gross_amount;
    }

    public static function getTotalRequestedAmount($req_no)
    {
        $totalRequestedAmount = Requisition::where('req_no', $req_no)->where('status','!=','Deleted')->where('status','!=','Edited')->sum('gross_amount');
        return $totalRequestedAmount;
    }

    public function deleteRetirementLine($ret_id)
    {
        $retirement= RetirementTemporaryTable::findOrFail($ret_id);
        $result = $retirement->where('id', $retirement->id)->delete();
        alert()->success('Requisition Line deleted successfuly', 'Good Job');
        return redirect()->back();
    }
}
