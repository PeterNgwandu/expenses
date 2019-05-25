<?php

namespace App\Http\Controllers\ExpenseRetirements;

use DB;
use App\Item\Item;
use App\Limits\Limit;
use App\Budget\Budget;
use App\Accounts\Account;
use Illuminate\Http\Request;
use App\StaffLevel\StaffLevel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\ExpenseRetirement\ExpenseRetirement;
use App\Temporary\ExpenseRetirementTemporaryTable;

class ExpenseRetirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stafflevels = StaffLevel::all();
        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        if (Auth::user()->stafflevel_id == $normalStaff) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->where('users.stafflevel_id',$normalStaff)
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Retired')
                               ->distinct('ret_no')

                               ->get();
            $ex_retirement_no_budget = DB::table('expense_retirements')
                                       ->join('users','expense_retirements.user_id','users.id')
                                       ->where('users.stafflevel_id',$normalStaff)
                                       ->where('expense_retirements.budget_id',0)
                                       ->select('expense_retirements.ret_no','users.username as username')
                                       ->distinct('ret_no')
                                       ->get();

        }elseif (Auth::user()->stafflevel_id == $supervisor) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$supervisor])
                               ->select('expense_retirements.ret_no','users.username as username')
                               // ->where('expense_retirements.gross_amount','>',5000)
                               // ->where('expense_retirements.gross_amount','<',500000)
                               ->where('expense_retirements.status', 'Retired')
                               ->distinct('ret_no')
                               ->get();
        }elseif (Auth::user()->stafflevel_id == $hod) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$hod])
                               ->select('expense_retirements.ret_no','users.username as username')
                               // ->whereBetween('expense_retirements.gross_amount', ['5000','5000000'])
                               ->where('expense_retirements.status', 'Retired')
                               ->distinct('ret_no')
                               ->get();
        }elseif (Auth::user()->stafflevel_id == $ceo) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$ceo])
                               ->select('expense_retirements.ret_no','users.username as username')
                               // ->whereBetween('expense_retirements.gross_amount', ['500000','5000000'])
                               ->where('expense_retirements.status', 'Retired')
                               ->distinct('ret_no')
                               ->get();
        }else{
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->where('users.stafflevel_id',[$financeDirector])
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Retired')
                               ->distinct('ret_no')

                               ->get();

        }

        return view('expense-retirements.manage-expense-retirements', compact('expense_retirements'));
    }

    public function pendingExpenseRetirement()
    {
        $stafflevels = StaffLevel::all();
        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        $limitNormalStaff = Limit::where('stafflevel_id',$normalStaff);
        $limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
        $ceoLimit = Limit::where('stafflevel_id',$ceo)->select('max_amount')->first();
        $limitSupervisor = Limit::where('stafflevel_id',$supervisor)->select('max_amount')->first();

        if (Auth::user()->stafflevel_id == $normalStaff) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->where('users.stafflevel_id',$normalStaff)
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Rejected')
                               ->orWhere('expense_retirements.status', 'Rejected By Supervisor')
                               ->orWhere('expense_retirements.status', 'Rejected By HOD')
                               ->orWhere('expense_retirements.status', 'Rejected By CEO')
                               ->orWhere('expense_retirements.status', 'Rejected By Finance')
                               ->distinct('ret_no')

                               ->get();
            $ex_retirement_no_budget = DB::table('expense_retirements')
                                       ->join('users','expense_retirements.user_id','users.id')
                                       ->where('users.stafflevel_id',$normalStaff)
                                       ->where('expense_retirements.budget_id',0)
                                       ->select('expense_retirements.ret_no','users.username as username')
                                       ->where('expense_retirements.status', 'Rejected')
                                       ->orWhere('expense_retirements.status', 'Rejected By Supervisor')
                                       ->orWhere('expense_retirements.status', 'Rejected By HOD')
                                       ->orWhere('expense_retirements.status', 'Rejected By CEO')
                                       ->orWhere('expense_retirements.status', 'Rejected By Finance')
                                       ->distinct('ret_no')
                                       ->get();

        }elseif (Auth::user()->stafflevel_id == $supervisor) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$normalStaff,$supervisor])
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Retired')
                               ->orWhere('expense_retirements.status', 'Rejected By HOD')
                               ->orWhere('expense_retirements.status', 'Rejected By CEO')
                               ->orWhere('expense_retirements.status', 'Rejected By Finance')
                               ->distinct('ret_no')
                               ->get();
        }elseif (Auth::user()->stafflevel_id == $hod) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->whereIn('users.stafflevel_id',[$normalStaff, $supervisor, $hod])
                               ->where('expense_retirements.gross_amount', '>=', $limitSupervisor->max_amount)
                               ->where('expense_retirements.status', 'Retired, supervisor')
                               ->orWhere('expense_retirements.status', 'Approved By Supervisor')
                               ->orWhere('expense_retirements.status', 'Rejected By CEO')
                               ->orWhere('expense_retirements.status', 'Rejected By Finance')
                               ->distinct('ret_no')
                               ->get();
        }elseif (Auth::user()->stafflevel_id == $ceo) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->where('expense_retirements.gross_amount', '>=', $limitHOD->max_amount)
                               ->whereIn('users.stafflevel_id',[$normalStaff, $supervisor, $hod, $ceo,$financeDirector])
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Retired, hod')
                               ->orWhere('expense_retirements.status', 'Approved By Finance')
                               ->orWhere('expense_retirements.status', 'Rejected By Finance')
                               ->distinct('ret_no')
                               ->get();
        }else{
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->where('users.stafflevel_id',[$financeDirector])
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Retired,ceo')
                               ->orWhere('expense_retirements.status', 'Approved By Supervisor')
                               ->orWhere('expense_retirements.status', 'Approved By HOD')
                               ->orWhere('expense_retirements.status', 'Rejected By CEO')
                               ->distinct('ret_no')

                               ->get();

        }

        return view('expense-retirements.manage-expense-retirements', compact('expense_retirements'));
    }

    public function confirmedExpenseRetirement()
    {
        $stafflevels = StaffLevel::all();
        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        if (Auth::user()->stafflevel_id == $normalStaff) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->where('users.stafflevel_id',$normalStaff)
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Confirmed')
                               ->distinct('ret_no')

                               ->get();
            $ex_retirement_no_budget = DB::table('expense_retirements')
                                       ->join('users','expense_retirements.user_id','users.id')
                                       ->where('users.stafflevel_id',$normalStaff)
                                       ->where('expense_retirements.budget_id',0)
                                       ->select('expense_retirements.ret_no','users.username as username')
                                       ->where('expense_retirements.status', 'Confirmed')
                                       ->distinct('ret_no')
                                       ->get();

        }elseif (Auth::user()->stafflevel_id == $supervisor) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$normalStaff,$supervisor])
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Confirmed')
                               ->distinct('ret_no')
                               ->get();
        }elseif (Auth::user()->stafflevel_id == $hod) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$hod,$supervisor,$normalStaff])
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Confirmed')
                               ->distinct('ret_no')
                               ->get();
        }elseif (Auth::user()->stafflevel_id == $ceo) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$ceo,$hod,$supervisor,$normalStaff,$financeDirector])
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Confirmed')
                               ->distinct('ret_no')
                               ->get();
        }else{
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$financeDirector,$supervisor,$hod,$normalStaff,$ceo])
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->where('expense_retirements.status', 'Confirmed')
                               ->distinct('ret_no')
                               ->get();

        }

        return view('expense-retirements.confirmed-expense-retirements', compact('expense_retirements'));
    }

    public function editExpenseRetirement($ret_no)
    {
        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        $expense_retirement = ExpenseRetirementTemporaryTable::join('budgets','expense_retirement_temporary_tables.budget_id', 'budgets.id')
                              ->join('items', 'expense_retirement_temporary_tables.item_id', 'items.id')
                              ->join('accounts', 'expense_retirement_temporary_tables.account_id', 'accounts.id')
                              ->select('expense_retirement_temporary_tables.*','budgets.title as budget', 'items.item_name as item','accounts.account_name as account')
                              ->where('ret_no', $ret_no)
                              ->where('expense_retirement_temporary_tables.status', '!=', 'Deleted')
                              ->get();
        return view('expense-retirements.edit-expense-retirement', compact('ret_no','expense_retirement'))->withItems($items)->withBudgets($budgets)->withAccounts($accounts);
    }

    public function sendExpenseRetirement($ret_no)
    {
        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;

        if(Auth::user()->stafflevel_id == $normalStaff){

            $status = 'Edited';
        }elseif(Auth::user()->stafflevel_id == $supervisor){
            $status = 'Edited';
        }elseif(Auth::user()->stafflevel_id == $hod){
            $status = 'Edited';
        }elseif(Auth::user()->stafflevel_id == $ceo){
            $status = 'Edited';
        }elseif(Auth::user()->stafflevel_id == $financeDirector){
            $status = 'Edited';
        }

        $expense_retirements = ExpenseRetirement::where('ret_no', $ret_no)->where('user_id', Auth::user()->id)->where('status', '!=', 'Edited')->where('status', '!=', 'Deleted')->distinct()->get();

        foreach($expense_retirements as $retirement)
        {
            $data = new ExpenseRetirementTemporaryTable();
            $data->id = $retirement->id;
            $data->ret_no = $retirement->ret_no;
            $data->budget_id = $retirement->budget_id;
            $data->item_id = $retirement->item_id;
            $data->account_id = $retirement->account_id;
            $data->user_id = $retirement->user_id;
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
            $data->created_at = $retirement->created_at;
            $data->status = $status;
            $data->save();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        return view('expense-retirements.create-expense-retirement')->withItems($items)->withBudgets($budgets)->withAccounts($accounts);
    }


    public function getItemsList($id)
    {
        $itemsList = Item::where('budget_id', $id)->get();
        return response()->json($itemsList);
    }

    public static function getLatestRetNoCount()
    {
        return ExpenseRetirement::select('ret_no')->latest()->distinct('ret_no')->count('ret_no');
    }

    public static function getLatestRetNo()
    {
        return ExpenseRetirement::select('ret_no')->latest()->first();
    }

    public static function getTheLatestExpenseRetirementNumber()
    {
        if (ExpenseRetirementController::getLatestRetNo() == null)
        {
            $ret_no = 'EX-RET-1';
        }elseif(ExpenseRetirementController::getLatestRetNo() != null) {
            //$getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            $ret_no = 'EX-RET-'.(ExpenseRetirementController::getLatestRetNoCount() + 1);
        }

        return $ret_no;
    }


    public function submit_expense_retire_row(Request $request)
    {
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

        if (ExpenseRetirement::select('ret_no')->latest()->first() == null)
        {
            $ret_no = 'EX-RET-1';
        }elseif(ExpenseRetirement::select('ret_no')->latest()->first() != null) {
            $getLatestRetNo = ExpenseRetirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            $ret_no = 'EX-RET-'.($getLatestRetNo + 1);
        }

        DB::table('expense_retirement_temporary_tables')->insert(['budget_id' => $request->budget_id,'item_id' => $request->item_id,'account_id' => $request->account_id, 'user_id' => $request->user_id, 'ret_no' => $request->ret_no, 'supplier_id' => $request->supplier_id, 'ref_no' => $request->ref_no, 'item_name' => $request->item_name2, 'purchase_date' => $request->purchase_date, 'unit_measure' => $request->unit_measure, 'quantity' => $request->quantity, 'unit_price' => $request->unit_price,
            'vat' => $request->vat, 'description' => $request->description, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount, 'status' => $request->status, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);

        if ($request->budget_id != 0) {
            $data = DB::table('expense_retirement_temporary_tables')
                    ->join('budgets','expense_retirement_temporary_tables.budget_id','budgets.id')
                    ->join('items','expense_retirement_temporary_tables.item_id','items.id')
                    ->join('accounts','expense_retirement_temporary_tables.account_id','accounts.id')
                    ->select('expense_retirement_temporary_tables.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')
                    ->where('ret_no', $request->ret_no)->get();

            $view = view('expense-retirements.render-expense-retired-items')->with('data', $data)->render();
        }elseif ($request->budget_id == 0) {
            $data = DB::table('expense_retirement_temporary_tables')
                    ->join('accounts','expense_retirement_temporary_tables.account_id','accounts.id')
                    ->select('expense_retirement_temporary_tables.*','accounts.account_name as account')
                    ->where('ret_no', $request->ret_no)->get();

            $view = view('expense-retirements.render-expense-retired-items')->with('data', $data)->render();
        }


        return response()->json(['result' => $view]);
    }

    public function submit_edit_expense_retire_row(Request $request, $ret_no)
    {
        $staff_levels = StaffLevel::all();
        $accounts = Account::all();

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

        if (ExpenseRetirement::select('ret_no')->latest()->first() == null)
        {
            $ret_no = 'EX-RET-1';
        }elseif(ExpenseRetirement::select('ret_no')->latest()->first() != null) {
            $getLatestRetNo = ExpenseRetirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            $ret_no = 'EX-RET-'.($getLatestRetNo + 1);
        }

        DB::table('expense_retirement_temporary_tables')->insert(['budget_id' => $request->budget_id,'item_id' => $request->item_id,'account_id' => $request->account_id, 'user_id' => $request->user_id, 'ret_no' => $ret_no, 'supplier_id' => $request->supplier_id, 'ref_no' => $request->ref_no, 'item_name' => $request->item_name2, 'purchase_date' => $request->purchase_date, 'unit_measure' => $request->unit_measure, 'quantity' => $request->quantity, 'unit_price' => $request->unit_price,
            'vat' => $request->vat, 'description' => $request->description, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount, 'status' => $status, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);

        if ($request->budget_id != 0) {
            $data = DB::table('expense_retirement_temporary_tables')
                    ->join('budgets','expense_retirement_temporary_tables.budget_id','budgets.id')
                    ->join('items','expense_retirement_temporary_tables.item_id','items.id')
                    ->join('accounts','expense_retirement_temporary_tables.account_id','accounts.id')
                    ->select('expense_retirement_temporary_tables.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')
                    ->where('ret_no', $request->ret_no)->get();

            $view = view('expense-retirements.render-edit-expense-retired-items', compact('accounts'))->with('data', $data)->render();
        }elseif ($request->budget_id == 0) {
            $data = DB::table('expense_retirement_temporary_tables')
                    ->join('accounts','expense_retirement_temporary_tables.account_id','accounts.id')
                    ->select('expense_retirement_temporary_tables.*','accounts.account_name as account')
                    ->where('ret_no', $request->ret_no)->get();

            $view = view('expense-retirements.render-edit-expense-retired-items', compact('accounts'))->with('data', $data)->render();
        }


        return response()->json(['result' => $view]);
    }

    public function permanentExpenseRetirementSubmission($exp_retire_no)
    {
        $retirements = ExpenseRetirementTemporaryTable::where('ret_no', $exp_retire_no)
                       ->where('user_id', Auth::user()->id)->get();

        foreach ($retirements as $retirement) {
            DB::table('expense_retirements')->insert([
                'budget_id' => $retirement->budget_id,
                'item_id' => $retirement->item_id,
                'account_id' => $retirement->account_id,
                'user_id' => $retirement->user_id,
                'ret_no' => $retirement->ret_no,
                'supplier_id' => $retirement->supplier_id,
                'purchase_date' => $retirement->purchase_date,
                'ref_no' => $retirement->ref_no,
                'item_name' => $retirement->item_name,
                'unit_measure' => $retirement->unit_measure,
                'quantity' => $retirement->quantity,
                'unit_price' => $retirement->unit_price,
                'vat' => $retirement->vat,
                'description' => $retirement->description,
                'vat_amount' => $retirement->vat_amount,
                'gross_amount' => $retirement->gross_amount,
                'status' => $retirement->status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        ExpenseRetirementTemporaryTable::truncate();

        session()->flash('message', 'Retirement has being created');
        return redirect()->back();
    }

    public function resetExpenseRetirement($user_id, $ret_no)
    {
        $result = ExpenseRetirementTemporaryTable::where('user_id', $user_id)->where('ret_no', $ret_no)->truncate();
        return response()->json(['result' => $result]);
    }

    public function deleteExpenseRetirementLine($ret_no, $data_id)
    {
        $result = ExpenseRetirementTemporaryTable::where('ret_no', $ret_no)->where('id', $data_id)->delete();
        return response()->json(['result' => $result]);
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
    public function show($ret_no)
    {
        $expense_retirements = ExpenseRetirement::where('ret_no', $ret_no)
                               ->join('budgets','expense_retirements.budget_id','budgets.id')
                               ->join('items','expense_retirements.item_id','items.id')
                               ->join('accounts','expense_retirements.account_id','accounts.id')
                               ->select('expense_retirements.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')
                               ->where('expense_retirements.status', '!=', 'Edited')
                               ->get();

        $expense_summary =  ExpenseRetirement::where('ret_no', $ret_no)
                               ->join('budgets','expense_retirements.budget_id','budgets.id')
                               ->join('items','expense_retirements.item_id','items.id')
                               ->join('accounts','expense_retirements.account_id','accounts.id')
                               ->select('expense_retirements.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')
                               ->where('expense_retirements.status', '!=', 'Edited')
                               ->first();

        $ex_retirement_no_budget = DB::table('expense_retirements')
                                       ->join('users','expense_retirements.user_id','users.id')
                                       ->join('accounts','expense_retirements.account_id','accounts.id')
                                       ->where('expense_retirements.budget_id',0)
                                       ->where('expense_retirements.ret_no',$ret_no)
                                       ->select('expense_retirements.*','users.username as username','accounts.account_name as account')
                                       ->where('expense_retirements.status', '!=', 'Edited')
                                       ->distinct('ret_no')
                                       ->get();

        return view('expense-retirements.show-expense-retirements', compact('expense_retirements','ret_no','ex_retirement_no_budget', 'expense_summary'));
    }

    public static function getExpenseRetirementTotal($ret_no)
    {
        return ExpenseRetirement::where('ret_no',$ret_no)->where('status', '!=', 'Edited')->sum('gross_amount');
    }

    public function approveExpenseRetirement($ret_no)
    {

        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;

        $limitNormalStaff = Limit::where('stafflevel_id',$normalStaff);
        $limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
        $ceoLimit = Limit::where('stafflevel_id',$ceo)->select('max_amount')->first();
        $limitSupervisor = Limit::where('stafflevel_id',$supervisor)->select('max_amount')->first();

        $expense_gross_amount = ExpenseRetirement::where('ret_no', $ret_no)->where('status', '!=', 'Edited')->sum('gross_amount');

        if (Auth::user()->stafflevel_id == 1)
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->where('status', '!=', 'Edited')->update([
                'status' => 'Approved By HOD',
            ]);
            return redirect(url('/expense_retirements/pending'));
        }elseif (Auth::user()->stafflevel_id == 2)
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->where('status', '!=', 'Edited')->update([
                'status' => 'Confirmed',
            ]);
            return redirect(url('/expense_retirements/pending'));
        }elseif (Auth::user()->stafflevel_id == 3)
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->where('status', '!=', 'Edited')->update([
                'status' => 'Approved By Supervisor',
            ]);
            return redirect(url('/expense_retirements/pending'));
        }elseif (Auth::user()->stafflevel_id == 5)
        {
            if($expense_gross_amount < $ceoLimit){
                $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->where('status', '!=', 'Edited')->update([
                  'status' => 'Confirmed',
                ]);
            }else{
                $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->where('status', '!=', 'Edited')->update([
                  'status' => 'Approved By Finance',
                ]);
            }
            return redirect(url('/expense_retirements/pending'));
        }
    }

    public function rejectExpenseRetirement($ret_no)
    {
        if (Auth::user()->stafflevel_id == 1)
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->where('status', '!=', 'Edited')->update([
                'status' => 'Rejected By HOD',
            ]);
            return view('expense-retirements.expense-retirement-comments', compact('ret_no'));
        }elseif (Auth::user()->stafflevel_id == 2)
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->where('status', '!=', 'Edited')->update([
                'status' => 'Rejected By CEO',
            ]);
            return view('expense-retirements.expense-retirement-comments', compact('ret_no'));
        }elseif (Auth::user()->stafflevel_id == 3)
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->where('status', '!=', 'Edited')->update([
                'status' => 'Rejected By Supervisor',
            ]);
            return view('expense-retirements.expense-retirement-comments', compact('ret_no'));
        }elseif (Auth::user()->stafflevel_id == 5)
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->where('status', '!=', 'Edited')->update([
                'status' => 'Rejected',
            ]);
            return view('expense-retirements.expense-retirement-comments', compact('ret_no'));
        }
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

    // public static function getExpenseRetirementTotal($ret_no)
    // {
    //     return Requisition::where('ret_no',$ret_no)->sum('gross_amount');
    // }

    public function updateExpenseRetirementSupplier($data_id, $supplier)
    {
        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'supplier_id' => $supplier,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirementRefNo($data_id, $ref_no)
    {
        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'ref_no' => $ref_no,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirementPurchaseDate($data_id, $purchase_date)
    {
        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'purchase_date' => $purchase_date,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirementItemName($data_id, $item_name)
    {
        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'item_name' => $item_name,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirementUnitMeasure($data_id, $unit_measure)
    {
        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'unit_measure' => $unit_measure,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirementQuantity($data_id, $quantity)
    {
        $retirement = ExpenseRetirementTemporaryTable::where('id', $data_id)->first();

        if ($retirement->vat == 'VAT Inclusive')
        {
            $vat_amount = (($quantity * $retirement->unit_price / 1.18) * 0.18);
            $gross_amount = ($quantity * $retirement->unit_price);
        }elseif($retirement->vat == 'VAT Exclusive')
        {
            $vat_amount = (($quantity * $retirement->unit_price * 0.18));
            $gross_amount = ($quantity * $retirement->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($quantity * $retirement->unit_price);
        }

        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'quantity' => $quantity,
            'vat_amount' => $vat_amount,
            'gross_amount' => $gross_amount
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirementUnitPrice($data_id, $unit_price)
    {
        $retirement = ExpenseRetirementTemporaryTable::where('id', $data_id)->first();

        if ($retirement->vat == 'VAT Inclusive')
        {
            $vat_amount = (($quantity * $retirement->unit_price / 1.18) * 0.18);
            $gross_amount = ($quantity * $retirement->unit_price);
        }elseif($retirement->vat == 'VAT Exclusive')
        {
            $vat_amount = (($quantity * $retirement->unit_price * 0.18));
            $gross_amount = ($quantity * $retirement->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($quantity * $retirement->unit_price);
        }

        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'unit_price' => $unit_price,
            'vat_amount' => $vat_amount,
            'gross_amount' => $gross_amount
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirementVat($data_id, $vat)
    {
        $retirement = ExpenseRetirementTemporaryTable::where('id', $data_id)->first();

        if ($retirement->vat == 'VAT Inclusive')
        {
            $vat_amount = (($retirement->quantity * $retirement->unit_price / 1.18) * 0.18);
            $gross_amount = ($retirement->quantity * $retirement->unit_price);
        }elseif($retirement->vat == 'VAT Exclusive')
        {
            $vat_amount = (($retirement->quantity * $retirement->unit_price * 0.18));
            $gross_amount = ($retirement->quantity * $retirement->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($retirement->quantity * $retirement->unit_price);
        }

        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'vat' => $vat,
            'vat_amount' => $vat_amount,
            'gross_amount' => $gross_amount
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirementAccount($data_id, $account)
    {
        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'account_id' => $account,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirementDescription($data_id, $description)
    {
        $result = ExpenseRetirementTemporaryTable::where('id', $data_id)->update([
            'description' => $description,
        ]);
        return response()->json(['result' => $result]);
    }

    public function updateExpenseRetirement($user_id, $ret_no)
    {
        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        $retirement = ExpenseRetirement::where('ret_no', $ret_no)->where('status', '!=', 'Edited')->update(['status' => 'Edited']);
        $editedRetirement = ExpenseRetirementTemporaryTable::where('ret_no', $ret_no)->where('user_id', $user_id)->where('status', 'Edited')->get();

        foreach($editedRetirement as $expense_retirement)
        {
            $data = new ExpenseRetirement();
            // $data->id = $expense_retirement->id;
            $data->ret_no = $expense_retirement->ret_no;
            $data->budget_id = $expense_retirement->budget_id;
            $data->item_id = $expense_retirement->item_id;
            $data->user_id = $expense_retirement->user_id;
            $data->account_id = $expense_retirement->account_id;
            $data->supplier_id = $expense_retirement->supplier_id;
            $data->ref_no = $expense_retirement->ref_no;
            $data->purchase_date = $expense_retirement->purchase_date;
            $data->item_name = $expense_retirement->item_name;
            $data->description = $expense_retirement->description;
            $data->unit_measure = $expense_retirement->unit_measure;
            $data->quantity = $expense_retirement->quantity;
            $data->unit_price = $expense_retirement->unit_price;
            $data->vat = $expense_retirement->vat;
            $data->vat_amount = $expense_retirement->vat_amount;
            $data->gross_amount = $expense_retirement->gross_amount;
            $data->created_at = $expense_retirement->created_at;
            $data->updated_at = $expense_retirement->updated_at;

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
            $data->status = $status;
            $data->save();


        }
        ExpenseRetirementTemporaryTable::where('ret_no', $ret_no)->where('user_id', $user_id)->truncate();
        // return redirect(url('expense_retirements/'.$ret_no));
    }

    public function budgetRestrict($budget_id)
    {
        $counter = null;
        $counter = ExpenseRetirementTemporaryTable::count();
        $retirement = ExpenseRetirementTemporaryTable::all();
        if($counter != 0){
            foreach($retirement as $retirement){
                if($retirement->budget_id != $budget_id){
                    return response()->json(['result' => $retirement->budget_id]);
                }
            }

        }
    }

}
