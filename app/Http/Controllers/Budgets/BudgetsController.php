<?php

namespace App\Http\Controllers\Budgets;

use DB;
use Alert;
use App\Item\Item;
use App\Budget\Budget;
use App\BudgetCategory;
use App\Accounts\Account;
use Illuminate\Http\Request;
use App\Department\Department;
use App\StaffLevel\StaffLevel;
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BudgetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::all();
        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;

        if(Auth::user()->username == 'Admin' || Auth::user()->stafflevel_id == $financeDirector || Auth::user()->stafflevel_id == $ceo)
        {
           $budgets = Budget::join('budget_categories','budgets.budget_category_id','budget_categories.id')
                   ->select('budgets.*','budget_categories.name as category')
                   ->get(); 
        }else
        {
            $budgets = Budget::join('budget_categories','budgets.budget_category_id','budget_categories.id')
                   ->select('budgets.*','budget_categories.name as category')
                   ->where('budgets.department_id', Auth::user()->department_id)
                   ->get();
        }   

        // $items = Item::where('title_no', $budgets[1]->title_no)->get();
        $items = Item::all();
        $total = 0;
        foreach ($items as $item) {
            $total = $total + $item->total;
        }
        return view('budgets.show-budgets')->withBudgets($budgets)->withItems($items)->withTotal($total)->withAccounts($accounts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Item::all();
        $accounts = Account::all();
        $budget_categories = BudgetCategory::all();
        $departments = Department::where('departments.status', 'Active')->get();
        $budgets = Budget::where('status', 'Confirmed')->limit(5)->latest()->get();

        return view('budgets.create-budget', compact('budget_categories'))->withBudgets($budgets)->withItems($items)->withAccounts($accounts)->withDepartments($departments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // $this->validate(request(), [
        //     'title_no' => 'required',
        //     'title' => 'required',
        //     'budget_category_id' => 'required',
        //     'description' => 'required',
        // ]);

        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        $budget = new Budget();
        $budget->title_no = BudgetsController::generateBudgetNo();
        $budget->budget_category_id = $request->budget_category_id;
        $budget->title = $request->title;
        $budget->description = $request->description;
        $budget->department_id = $request->department_id;
        $budget->user_id = $request->user_id;

        if(Auth::user()->stafflevel_id == $supervisor)
        {
            $budget->status = 'onprocess';
        }elseif(Auth::user()->stafflevel_id == $hod)
        {
            $budget->status = 'onprocess, hod';
        }elseif(Auth::user()->stafflevel_id == $financeDirector)
        {
            $budget->status = 'onprocess, finance';
        }elseif(Auth::user()->stafflevel_id == $ceo)
        {
            $budget->status = 'onprocess, ceo';
        }

        $budget->save();

        return redirect(url('/budgets'));
    }

    public function approveBudget($budget_id)
    {
        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        $budget = Budget::where('id', $budget_id)->first();

        $budgetLinesCheck = Budget::join('items','budgets.id','items.budget_id')->where('items.budget_id', $budget_id)->get();

        if(Auth::user()->stafflevel_id == $hod && $budget->status == 'onprocess' || $budget->status == 'Edited')
        {
            
            if ($budgetLinesCheck->isEmpty()) {
                return response()->json(['result' => $budgetLinesCheck]);
            }

            $result = Budget::where('id', $budget_id)->update([
                        'status' => 'Approved By HOD',
                        'approver_id' => $hod,
                      ]);
        }elseif(Auth::user()->stafflevel_id == $ceo)
        {
            
            if($budget->status != 'Approved')
            {
                alert()->error('Opps!', 'Budget not yet approved by Finance Director');
                return redirect()->back();
            }elseif($budget->status == 'Approved' || $budget->status == 'onprocess, finance' || $budget->status == 'Edited, Finance')
            {
                $result = Budget::where('id', $budget_id)->update([
                    'status' => 'Confirmed',
                    'approver_id' => $ceo,
                ]);
            }
                
        }elseif(Auth::user()->stafflevel_id == $financeDirector){

            if ($budgetLinesCheck->isEmpty()) {
                return response()->json(['result' => $budgetLinesCheck]);
            }
            elseif($budget->status == 'Approved By HOD' || $budget->status == 'Edited, HOD' || $budget->status == 'Edited, CEO' || $budget->status == 'onprocess, hod')
            {
                $result = Budget::where('id', $budget_id)->update([
                    'status' => 'Approved',
                    'approver_id' => $financeDirector,
                ]);
            }
        }

        return response()->json(['result' => $result]);
        
    }

    public function rejectBudget($budget_id)
    {
        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        $budgetLinesCheck = Budget::join('items','budgets.id','items.budget_id')->where('items.budget_id', $budget_id)->get();

        if(Auth::user()->stafflevel_id == $ceo){
            $budget = Budget::where('id', $budget_id)->first();
            if($budget->status == 'null')
            {
                alert()->error('Opps!', 'Budget not yet approved by Finance Director');
                return redirect()->back();
            }
            // $result = Budget::where('id', $budget_id)->update([
            //     'status' => 'Confirmed',
            //     'approver_id' => $ceo,
            // ]);
            $result = Budget::where('id', $budget_id)->update([
                'status' => 'Rejected',
                'approver_id' => $ceo,
            ]);
        }elseif(Auth::user()->stafflevel_id == $financeDirector){
            $result = Budget::where('id', $budget_id)->update([
                'status' => 'Rejected',
                'approver_id' => $financeDirector,
            ]);
        }elseif(Auth::user()->stafflevel_id == $hod){

            if ($budgetLinesCheck->isEmpty()) {
                return response()->json(['result' => $budgetLinesCheck]);
            }

            $result = Budget::where('id', $budget_id)->update([
                        'status' => 'Rejected By HOD',
                        'approver_id' => $hod,
                      ]);
        }

        return response()->json(['result' => $result]);
    }

    public function rejectedBudgets()
    {
        $accounts = Account::all();
        $staff_levels = StaffLevel::all();
        $budgets = Budget::join('budget_categories','budgets.budget_category_id','budget_categories.id')
                   ->select('budgets.*','budget_categories.name as category')
                   ->where('user_id', Auth::user()->id)
                   ->where('status', 'Rejected By HOD')
                   ->orWhere('status', 'Rejected')
                   ->distinct()
                   ->get();

        return view('budgets.rejected-budgets')->withBudgets($budgets)->withAccounts($accounts);           
    }

    public function deleteBudget($budget_id)
    {
        $result = Budget::where('id', $budget_id)->delete();
        return response()->json(['result' => $result]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $budget = Budget::findOrFail($id);
        $itemsUnderBudget = Item::where('budget_id', $budget->id)->get();
        $total = 0;

        $accounts = Account::all();
        $budgets = Budget::join('budget_categories','budgets.budget_category_id','budget_categories.id')
                   ->select('budgets.*','budget_categories.name as category')
                   // ->where('status', 'Confirmed')
                   ->get();
        $items = Item::all();

        foreach ($items as $item) {
            $total = $total + $item->total;
        }
        return view('budgets.view-budget', compact('id','itemsUnderBudget'))->withBudget($budget)->withBudgets($budgets)->withAccounts($accounts)->withItems($items)->withTotal($total);
    }

    public function freezeBudget($budget_id)
    {
        Budget::where('id', $budget_id)->update(['is_active' => 'Frozen']);

        // alert()->success('Budget Frozen', 'Done');
        return redirect()->back();
    }

    public function unfreezeBudget($budget_id)
    {
        Budget::where('id', $budget_id)->update(['is_active' => 'Active']);

        // alert()->success('Budget is now active', 'Activated');
        return redirect()->back();
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

    public static function get_items_by_budgetID($id){
        return DB::table('items')->where('budget_id', $id)->get();
       //return Item::where('budget_id', $id)->get();
    }

    public static function get_sumitems_by_budgetID($id){
        return DB::table('items')->where('budget_id', $id)->SUM('total');
    }

    public static function getCommitedAmount($id)
    {
        return DB::table('requisitions')->where('budget_id', $id)->where('status','Approve By Paid')->sum('gross_amount');
    }

    public static function getSpentAmount($id)
    {
        return BudgetsController::get_sumitems_by_budgetID($id) - BudgetsController::getCommitedAmount($id);
    }

    public static function getBudgetBalance($id)
    {
        return BudgetsController::get_sumitems_by_budgetID($id) - BudgetsController::getSpentAmount($id);
    }

    public static function get_budget_by_id($id) {
        return Budget::where('id', $id)->first();
    }

    public static function getLatestBudgetNo()
    {
        return Budget::select('title_no')->latest()->first();
    }

    public static function getLatestBudgetNoCount()
    {
        return Budget::select('title_no')->distinct()->count();
    }

    public static function generateBudgetNo()
    {
        $title_no = null;
        if(BudgetsController::getLatestBudgetNo() == null)
        {
            $title_no = (Carbon::now()->year)."-BGT-1";
        }elseif(BudgetsController::getLatestBudgetNo() != null)
        {
            $title_no_count = BudgetsController::getLatestBudgetNoCount();
            $title_no = (Carbon::now()->year)."-BGT-".($title_no_count + 1);
        }
        return $title_no;
    }

    public static function totalBudgetById($budget_id)
    {
        return DB::table('items')->where('budget_id', $budget_id)->sum('total');
    }

    public function getTotalBudgetAmount($budget_id)
    {
        $total = Item::where('budget_id', $budget_id)->sum('total');

        $total_requested = Requisition::join('budgets','requisitions.budget_id','budgets.id')
                                          ->join('budget_categories','budgets.budget_category_id','budget_categories.id')
                                          ->where('budgets.id', $budget_id)
                                          // ->where('budget_category_id', 1)
                                          ->where('requisitions.status','!=','Edited')
                                          ->where('requisitions.status','!=','Deleted')
                                          ->sum('gross_amount');

        $total_avalilable = $total - $total_requested;                                  

        return response()->json(['result' => $total_avalilable]);
    }
}
