<?php

namespace App\Http\Controllers\ExpenseRetirements;

use DB;
use App\Item\Item;
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

        // $expense_retirements = DB::table('expense_retirements')
        //                        ->join('budgets','expense_retirements.budget_id','budgets.id')
        //                        ->join('users','expense_retirements.user_id','users.id')
        //                        ->select('expense_retirements.ret_no','budgets.title as budget','users.username as username')
        //                        ->distinct('ret_no')
        //                        ->get();

        if (Auth::user()->stafflevel_id == $normalStaff) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->where('users.stafflevel_id',$normalStaff)
                               ->select('expense_retirements.ret_no','users.username as username')
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
                               ->whereIn('users.stafflevel_id',[$normalStaff,$supervisor])
                               ->select('expense_retirements.ret_no','users.username as username')
                               // ->where('expense_retirements.gross_amount','>',5000)
                               // ->where('expense_retirements.gross_amount','<',500000)
                               ->distinct('ret_no')
                               ->get();
        }elseif (Auth::user()->stafflevel_id == $hod) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$normalStaff,$supervisor,$hod])
                               ->select('expense_retirements.ret_no','users.username as username')
                               // ->whereBetween('expense_retirements.gross_amount', ['5000','5000000'])
                               ->distinct('ret_no')
                               ->get();
        }elseif (Auth::user()->stafflevel_id == $ceo) {
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->whereIn('users.stafflevel_id',[$normalStaff,$supervisor,$hod,$ceo])
                               ->select('expense_retirements.ret_no','users.username as username')
                               // ->whereBetween('expense_retirements.gross_amount', ['500000','5000000'])
                               ->distinct('ret_no')
                               ->get();
        }else{
            $expense_retirements = DB::table('expense_retirements')
                               ->join('users','expense_retirements.user_id','users.id')
                               ->where('users.stafflevel_id',[$normalStaff,$supervisor,$hod,$ceo,$financeDirector])
                               ->select('expense_retirements.ret_no','users.username as username')
                               ->distinct('ret_no')
                               ->get();
                               
        }

        return view('expense-retirements.manage-expense-retirements', compact('expense_retirements'));
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
                               ->get();

        $ex_retirement_no_budget = DB::table('expense_retirements')
                                       ->join('users','expense_retirements.user_id','users.id')
                                       ->join('accounts','expense_retirements.account_id','accounts.id')
                                       ->where('expense_retirements.budget_id',0)
                                       ->where('expense_retirements.ret_no',$ret_no)
                                       ->select('expense_retirements.*','users.username as username','accounts.account_name as account') 
                                       ->distinct('ret_no')
                                       ->get();                       

        return view('expense-retirements.show-expense-retirements', compact('expense_retirements','ret_no','ex_retirement_no_budget'));
    }

    public static function getExpenseRetirementTotal($ret_no)
    {
        return ExpenseRetirement::where('ret_no',$ret_no)->sum('gross_amount');
    }

    public function approveExpenseRetirement($ret_no)
    {
        if (Auth::user()->stafflevel_id == 1) 
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->update([
                'status' => 'Approved By HOD',
            ]);
            return redirect()->back();
        }elseif (Auth::user()->stafflevel_id == 2) 
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->update([
                'status' => 'Approved By CEO',
            ]);
            return redirect()->back();
        }elseif (Auth::user()->stafflevel_id == 3) 
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->update([
                'status' => 'Approved By Supervisor',
            ]);
            return redirect()->back();
        }elseif (Auth::user()->stafflevel_id == 5) 
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->update([
                'status' => 'Confirmed',
            ]);
            return redirect()->back();
        } 
    }

    public function rejectExpenseRetirement($ret_no)
    {
        if (Auth::user()->stafflevel_id == 1) 
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->update([
                'status' => 'Rejected By HOD',
            ]);
            return view('expense-retirements.expense-retirement-comments', compact('ret_no'));
        }elseif (Auth::user()->stafflevel_id == 2) 
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->update([
                'status' => 'Rejected By CEO',
            ]);
            return view('expense-retirements.expense-retirement-comments', compact('ret_no'));
        }elseif (Auth::user()->stafflevel_id == 3) 
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->update([
                'status' => 'Rejected By Supervisor',
            ]);
            return view('expense-retirements.expense-retirement-comments', compact('ret_no'));
        }elseif (Auth::user()->stafflevel_id == 5) 
        {
            $result = DB::table('expense_retirements')->where('ret_no',$ret_no)->update([
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
}
