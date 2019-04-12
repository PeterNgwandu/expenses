<?php

namespace App\Http\Controllers\Requisitions;

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
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Accounts\FinanceSupportiveDetail;
use App\Temporary\RequisitionTemporaryTable;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;

class RequisitionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $pending_requisitions = RequisitionTemporaryTable::where('requisition_temporary_tables.user_id', $user_id)->join('budgets','requisition_temporary_tables.budget_id','budgets.id')->join('items','requisition_temporary_tables.item_id','items.id')->join('accounts','requisition_temporary_tables.account_id','accounts.id')->join('users','requisition_temporary_tables.user_id','users.id')->join('departments','users.department_id','departments.id')->select('requisition_temporary_tables.*','departments.*','budgets.title as budget','items.item_name as item','accounts.account_name as account','users.username as username','departments.name as department')->get();
        return view('requisitions.view-requisitions', compact('pending_requisitions'));
    }

    public function pendingRequsitionsHandling()
    {
        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;
        $user = User::where('id', Auth::user()->id)->first();
        $user_dept = User::join('departments','users.department_id','departments.id')
                          ->where('departments.id', Auth::user()->department_id)
                          ->select('users.department_id as dept_id')
                          ->distinct('dept_id')
                          ->first();

        if(Auth::user()->stafflevel_id == $normalStaff)
        {
          $pending_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no
                                  FROM `requisitions`
                                  JOIN users on requisitions.user_id = users.id
                                  JOIN departments on users.department_id = departments.id

                                  WHERE requisitions.status
                                  LIKE '%Rejected%' AND users.stafflevel_id NOT IN ($supervisor,$hod,$ceo,$financeDirector)"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif(Auth::user()->stafflevel_id == $supervisor)
        {
          $pending_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no
                                  FROM `requisitions`
                                  JOIN users on requisitions.user_id = users.id
                                  JOIN departments on users.department_id = departments.id
                                  WHERE requisitions.status
                                  LIKE '%Rejected%' AND users.stafflevel_id IN ($normalStaff,$supervisor) AND users.stafflevel_id NOT IN ($hod,$financeDirector,$ceo)"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $hod)
        {
          $pending_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no
                                  FROM `requisitions`
                                  JOIN users on requisitions.user_id = users.id
                                  JOIN departments on users.department_id = departments.id
                                  WHERE requisitions.status
                                  LIKE '%Rejected%' AND users.stafflevel_id IN ($normalStaff,$supervisor,$hod,$financeDirector)"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $ceo)
        {
          $pending_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no
                                  FROM `requisitions`
                                  JOIN users on requisitions.user_id = users.id
                                  JOIN departments on users.department_id = departments.id
                                  WHERE requisitions.status
                                  LIKE '%Rejected%' AND users.stafflevel_id IN ($normalStaff,$supervisor,$hod,$financeDirector)"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $financeDirector)
        {
            $pending_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no
                                    FROM `requisitions`
                                    JOIN users on requisitions.user_id = users.id
                                    JOIN departments on users.department_id = departments.id
                                    WHERE requisitions.status
                                    LIKE '%Rejected%'"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = Auth::user()->id;
        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();

        return view('requisitions.create-requisition')->withBudgets($budgets)->withItems($items)->withData($data)->withAccounts($accounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


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
        $submitted_requisitions = Requisition::where('requisitions.id', $id)
                                ->join('budgets','requisitions.budget_id','budgets.id')
                                ->join('items','requisitions.item_id','items.id')
                                ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                                ->select('requisitions.*','budgets.title as budget','items.item_name as item','finance_supportive_details.amount_paid as paid')
                                ->distinct('req_no')
                                ->get();

        $user_id = Auth::user()->id;
        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();
        $req_id = Requisition::findOrFail($id);
        $requisition = Requisition::where('id',$id)->first();
        return view('requisition.edit-requisition', compact('req_id','user_id','items','budgets','accounts','requisition','submitted_requisitions','id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $req_id)
    {
        $requisition = Requisition::findOrFail($req_id);

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

        $result = DB::table('requisitions')->where('id',$req_id)->update([
                'budget_id' => $request->budget_id,
                'item_id' => $request->item_id,
                'account_id' => $request->account_id,
                'user_id' => $request->user_id,
                'serial_no' => $request->serial_no,
                'req_no' => $request->req_no,
                'item_name' => $request->item_name,
                'description' => $request->description,
                'unit_measure' => $request->unit_measure,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'vat' => $request->vat,
                'vat_amount' => $vat_amount,
                'gross_amount' =>$gross_amount,
                'status' => $requisition->status,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

            alert()->error('Requisition edited successfuly', 'Good Job');
        return response()->json(['result' => $result]);

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

    public function getItemsList($id)
    {
        $itemsList = Item::where('budget_id', $id)->get();
        return response()->json($itemsList);
    }

    public function add_new_row (){
        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        $view = view('requisitions.new-requisition-row')->withBudgets($budgets)->withItems($items)->withAccounts($accounts)->render();
        return response()->json(['result' => $view]);
    }

    public function submit_single_row($budget, $item, $accounts) {
        $user_id = Auth::user()->id;
        $items = Item::all();
        $budgets = Budget::all();
        $account = Account::all();

        // Added by Peter


        if (Requisition::select('req_no')->latest()->first() == null) {
            $req_no = 'Req-1';
        }elseif(Requisition::select('req_no')->latest()->first() != null) {
            $getLatestReqNo = Requisition::select('req_no')->latest()->distinct('req_no')->count('req_no');
            $req_no = 'Req-'.($getLatestReqNo + 1);
        }


        DB::table('requisition_temporary_tables')->insert(['budget_id' => $budget, 'item_id' => $item, 'user_id' => $user_id, 'account_id' => $accounts, 'req_no' => $req_no, 'status' => 'onprocess']);

       $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();

        $view = view('requisitions.requisition-form')->withBudgets($budgets)->withItems($items)->withData($data)->withAccounts($account)->render();
        return response()->json(['result' => $view]);
    }

    public static function get_latest_row_from_temporary(){
        $user_id = Auth::user()->id;
        return RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->orderBy('id', 'DESC')->first();
    }

    public function deleteRowByBudgetID($rowID)
    {
        $rowID = RequisitionTemporaryTable::where('id', $rowID)->delete();
        return redirect()->route('requisitions.create');
    }

    public function updateUnitMeasure($rowID, $value)
    {
        // $rowID = RequisitionTemporaryTable::findOrFail($rowID);
         DB::table('requisition_temporary_tables')->where('id', $rowID)->update(['unit_measure' => $value]);
        return response()->json(['result' => $rowID]);

    }

    public function updateItemDescription($rowID, $value)
    {
        DB::table('requisition_temporary_tables')->where('id', $rowID)->update(['description' => $value]);
        return response()->json(['result' => $value]);
    }

    public function updateItemUnitPrice($rowID, $value)
    {

        $vat = 18.0;
        $priceExcludeVat = $value;
        $vatToPay = ($priceExcludeVat / 100) * $vat;
        $totalPrice = $priceExcludeVat + $vatToPay;



        DB::table('requisition_temporary_tables')->where('id', $rowID)->update(['unit_price' => $value]);
        return response()->json(['result' => $value]);
    }

    public function updateItemQuantity($rowID, $value)
    {
        DB::table('requisition_temporary_tables')->where('id', $rowID)->update(['quantity' => $value]);
        return response()->json(['result' => $value]);
    }

    public static function getItemBudgetTotal($rowID)
    {
        return DB::table('requisition_temporary_tables')->where('id', $rowID)
                        ->SUM(DB::raw('requisition_temporary_tables.quantity * requisition_temporary_tables.unit_price'));
    }

    public function submitRequisition($user_id)
    {

        $pending_requisitions = RequisitionTemporaryTable::where('user_id', $user_id)->get();



        foreach ($pending_requisitions as $pending_requisition) {

            $requisition = new Requisition();
            $requisition->budget_id = $pending_requisition->budget_id;
            $requisition->user_id = Auth::user()->id;
            $requisition->item_id = $pending_requisition->item_id;
            $requisition->account_id = $pending_requisition->account_id;
            $requisition->req_no = $pending_requisition->req_no;
            $requisition->item_name = $pending_requisition->item_name;
            $requisition->unit_measure = $pending_requisition->unit_measure;
            $requisition->unit_price = $pending_requisition->unit_price;
            $requisition->quantity = $pending_requisition->quantity;
            $requisition->description = $pending_requisition->description;
            $requisition->vat = $pending_requisition->vat;
            $requisition->vat_amount = $pending_requisition->vat_amount;
            $requisition->gross_amount = $pending_requisition->gross_amount;
            $requisition->status = $pending_requisition->status;

            $requisition->save();
            $pending_requisition->truncate();

        }

        return redirect()->route('submitted-requisitions');
    }

    public function submittedRequisition()
    {
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
                                      ->join('staff_levels','users.stafflevel_id','staff_levels.id')
                                      ->join('limits','staff_levels.id','limits.stafflevel_id')
                                      ->join('departments','users.department_id','departments.id')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                      ->where('users.stafflevel_id','!=', $ceo)
                                      ->whereIn('users.stafflevel_id',[$hod,$supervisor,$normalStaff,$financeDirector])
                                      ->where('departments.id',$user_dept->dept_id)
                                      ->where('requisitions.status', 'like', '%Approved%')
                                      ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      // ->whereBetween('requisitions.gross_amount', [0,$limitHOD->max_amount])
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
                                      ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      // ->where('requisitions.gross_amount','>',$limitNormalStaff->max_amount)
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
                                      ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      ->whereIn('users.stafflevel_id', [$hod,$financeDirector])
                                      // ->orWhere('users.stafflevel_id', ['4','3'])
                                      // ->whereBetween('requisitions.gross_amount', ['500000','5000000'])

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
                                      ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      ->whereIn('users.stafflevel_id', [$normalStaff])
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
                                      ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
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
                                      ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      ->orWhere('requisitions.status', 'like', '%Paid%')
                                      ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      ->distinct('req_no')
                                      ->get();
            return view('requisitions.finance-requisitions', compact('submitted_requisitions','staff_levels','requisition','Submittedrequisitions'))->withUser($user);
        }

    }

    public function submittedRequisitions($req_no)
    {
        $submitted_requisitions = Requisition::where('requisitions.req_no', $req_no)
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->join('items','requisitions.item_id','items.id')
                                  ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                                  ->select('requisitions.*','budgets.title as budget','items.item_name as item','finance_supportive_details.amount_paid as paid')
                                  ->distinct('req_no')
                                  ->get();

        return view('requisitions.all-requisitions', compact('submitted_requisitions','req_no','amount_paid'));
    }

    public static function getAmountPaid($req_no,$serial_no)
    {
        return FinanceSupportiveDetail::where('req_no',$req_no)->where('serial_no',$serial_no)->sum('amount_paid');
    }

    public static function getTotalPerSerialNo($req_no, $serial_no)
    {
        return Requisition::where('req_no',$req_no)->where('serial_no',$serial_no)->sum('gross_amount');
    }

    public static function getTotalAmountPaid($req_no)
    {
        return FinanceSupportiveDetail::where('req_no',$req_no)->sum('amount_paid');
    }

    public static function getTotalofTotals($uid)
    {
        return Requisition::where('user_id', $uid)->sum('gross_amount');
    }

    public static function getStaffByLevel($user_id)
    {
        $user_id = Auth::user()->id;
        $staff = DB::table('users')->join('staff_levels','staff_levels.id','users.stafflevel_id')
                 ->join('requisitions','requisitions.user_id','users.id')
                 ->where('users.id', $user_id)
                 ->select('user_id')
                 ->distinct('user_id')
                 ->get();
    }

    public function approveRequisition($requisition_no)
    {

        $userSessionID = Session::getId();
        $stafflevel_id = StaffLevel::where('id', Auth::user()->id)->select('staff_levels.id')->first();

        $requisitionID = Requisition::where('req_no',$requisition_no)->first();
        $requisitionUserID = Requisition::where('requisitions.user_id', Auth::user()->id)->first();
        $requisitionTotal = Requisition::where('requisitions.req_no', $requisition_no)
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->select('requisitions.gross_amount','requisitions.gross_amount')
                                  ->distinct('user_id')
                                  ->get();

        $budgetTotal = Budget::join('items','items.budget_id','budgets.id')
                             ->join('requisitions','requisitions.budget_id','budgets.id')
                             ->where('requisitions.req_no',$requisition_no)
                             ->sum('items.total');

        $user_id = Auth::user()->id;

        $limitTotal = Limit::join('staff_levels','limits.stafflevel_id','staff_levels.id')
                           ->join('users','users.stafflevel_id','staff_levels.id')
                           ->select('limits.max_amount')
                           ->where('users.id', $user_id)
                           ->get();

          if (Auth::user()->stafflevel_id == 4) {
              alert()->error('Oops! Can\'t reject the requisition', 'Error');
              return redirect()->back();
          }

          if (Auth::user()->stafflevel_id == 2)
          {
              $user_id = Auth::user()->id;
              $stafflevel_id = Auth::user()->stafflevel_id;
              $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                          ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                          ->select('users.username as username')
                          ->first();

              if ($requisitionID->status == 'Confirmed' || $requisitionID->status == 'Paid')
              {
                alert()->error('Oops! Already Confirmed or Paid by Finance', 'Error');
                return redirect()->back();
              }



              $result = DB::table('requisitions')->where('req_no', $requisition_no)->update([
                  'status' => "Approved By ". $approver->username,
              ]);

              return redirect()->back();

          }elseif (Auth::user()->stafflevel_id == 3)
          {
              $stafflevel_id = Auth::user()->stafflevel_id;
              $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                          ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                          ->select('users.username as username')
                          ->first();

              if ($requisitionID->status == 'Confirmed' || $requisitionID->status == 'Paid')
              {
                alert()->error('Oops! Already Confirmed or Paid by Finance', 'Error');
                return redirect()->back();
              }

              if ($requisitionID->status == Requisition::where('req_no',$requisition_no)->where('status', 'like', '%'. 'Approved By' . '%')->select('requisitions.status')->first()) {
                  return redirect()->back();
              }

              $result = DB::table('requisitions')->where('req_no', $requisition_no)->update([
                  'status' => "Approved By ". $approver->username,
              ]);
              return redirect()->back();
          }elseif (Auth::user()->stafflevel_id == 1)
          {
              $stafflevel_id = Auth::user()->stafflevel_id;
              $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                          ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                          ->select('users.username as username')
                          ->first();

              if ($requisitionID->status == 'Confirmed' || $requisitionID->status == 'Paid')
              {
                 alert()->error('Oops! Already Confirmed or Paid by Finance', 'Error');
                 return redirect()->back();
              }

              $result = DB::table('requisitions')->where('req_no', $requisition_no)->update([
                  'status' => "Approved By ". $approver->username,
              ]);
              return redirect()->back();


          }elseif (Auth::user()->stafflevel_id == 5)
          {


              $stafflevel_id = Auth::user()->stafflevel_id;
              $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                          ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                          ->select('users.username as username')
                          ->first();
              $financeStaffs = User::join('departments','departments.id','users.department_id')
                                   ->where('departments.name','Finance')
                                   ->where('users.username', '!=', 'CEO')
                                   ->get();

              $accounts = Account::all();

              if (Requisition::where('req_no',$requisition_no)->sum('gross_amount') == FinanceSupportiveDetail::where('req_no',$requisitionID->req_no)->sum('amount_paid') || FinanceSupportiveDetail::where('req_no',$requisitionID->req_no)->sum('amount_paid') > Requisition::where('req_no',$requisition_no)->sum('gross_amount')) {
                  alert()->error('Opps! You cannot pay more than requested amount', 'Error')->persistent('Close');
                  return redirect(url('submitted-requisitions/'.$requisition_no));
              }

              if ($requisitionID->status == 'Paid') {

                  return view('requisition.finance-supportive-details', compact('requisitionID','financeStaffs','accounts','requisition_no'));
              }

              $result = DB::table('requisitions')->where('req_no', $requisition_no)->update([
                  'status' => "Confirmed"
              ]);

              alert()->success('Requisition approved successful', 'Good Job');
              // if ($requisitionID->status == 'Confirmed') {
              //
              //     return view('requisition.finance-supportive-details', compact('requisitionID','financeStaffs','accounts','requisition_no'));
              // }else{
              //
              // }

              return redirect()->back();


          }



    }

    public function processPayment($req_no)
    {
        $requisitionID = Requisition::where('req_no',$req_no)->first();
        $financeStaffs = User::join('departments','departments.id','users.department_id')
                             ->where('departments.name','Finance')
                             ->where('users.username', '!=', 'CEO')
                             ->get();

        $accounts = Account::all();
        $requisitions = Requisition::all();
        return view('requisition.finance-supportive-details', compact('requisitionID','financeStaffs','accounts','req_no','requisitions'));
    }

    public function rejectRequisition($requisition_no)
    {

        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        $userSessionID = Session::getId();
        $stafflevel_id = StaffLevel::where('id', Auth::user()->id)->select('staff_levels.id')->first();
        $requisitionID = Requisition::where('id',$requisition_no)->first();
        $requisitionUserID = Requisition::where('requisitions.user_id', Auth::user()->id)->first();
        $requisitionTotal = Requisition::where('requisitions.req_no', $requisition_no)
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->select('requisitions.gross_amount','requisitions.gross_amount')
                                  ->distinct('user_id')
                                  ->first();

        $budgetTotal = Budget::join('items','items.budget_id','budgets.id')
                             ->join('requisitions','requisitions.budget_id','budgets.id')
                             ->where('requisitions.id',$requisition_no)
                             ->sum('items.total');

        $user_id = Auth::user()->id;

        $limitTotal = Limit::join('staff_levels','limits.stafflevel_id','staff_levels.id')
                           ->join('users','users.stafflevel_id','staff_levels.id')
                           ->select('limits.max_amount')
                           ->where('users.id', $user_id)
                           ->get();

          if (Auth::user()->stafflevel_id == 4) {
              alert()->error('Oops! Can\'t reject the requisition', 'Error');
              return redirect()->back();
          }

          if (Auth::user()->stafflevel_id == 2)
          {
              $user_id = Auth::user()->id;
              $stafflevel_id = Auth::user()->stafflevel_id;
              $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                          ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                          ->select('users.username as username')
                          ->first();
              $result = DB::table('requisitions')->where('req_no', $requisition_no)->update([
                  'status' => "Rejected By ". $approver->username,
              ]);
              return view('requisition.requisition-comments', compact('requisition_no'));

          }elseif (Auth::user()->stafflevel_id == 3)
          {
              $stafflevel_id = Auth::user()->stafflevel_id;
              $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                          ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                          ->select('users.username as username')
                          ->first();
              $result = DB::table('requisitions')->where('req_no', $requisition_no)->update([
                  'status' => "Rejected By ". $approver->username,
              ]);
              return view('requisition.requisition-comments', compact('requisition_no'));
          }elseif (Auth::user()->stafflevel_id == 1)
          {
              $stafflevel_id = Auth::user()->stafflevel_id;
              $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                          ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                          ->select('users.username as username')
                          ->first();
              $result = DB::table('requisitions')->where('req_no', $requisition_no)->update([
                  'status' => "Rejected By ". $approver->username,
              ]);

              return view('requisition.requisition-comments', compact('requisition_no'));


          }elseif (Auth::user()->stafflevel_id == 5)
          {
              $stafflevel_id = Auth::user()->stafflevel_id;
              $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                          ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                          ->select('users.username as username')
                          ->first();
              $result = DB::table('requisitions')->where('req_no', $requisition_no)->update([
                  'status' => "Rejected"
              ]);
              return view('requisition.requisition-comments', compact('requisition_no'));

          }

    }

    public function approvedRequisitions()
    {
        $approved_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no, requisitions.status
                                  FROM `requisitions`
                                  JOIN users on requisitions.user_id = users.id
                                  JOIN departments on users.department_id = departments.id
                                  WHERE requisitions.status
                                  = 'Confirmed'"));
        return view('requisition.approved-requisitions', compact('approved_requisitions'));
    }

    public function paidRequisitions()
    {
        $paid_requisitions = Requisition::where('requisitions.status','Paid')
                                 ->join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 ->get();
        return view('requisition.paid-requisitions', compact('paid_requisitions'));
    }

    public function retiredRequisitions()
    {
        $retired_requisitions = Requisition::where('requisitions.status','Retired')
                                 ->join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 ->get();
        return view('requisition.retired-requisitions', compact('retired_requisitions'));
    }

    public function deleteRequsitionLine($req_no, $req_id)
    {
        $requisition = Requisition::findOrFail($req_id);
        $requisition->where('req_no', $req_no)->where('id', $req_id)->update(['status' => 'Deleted']);
        alert()->success('Requisition Line deleted successfuly', 'Good Job');
        return redirect(url('submitted-requisitions'));
    }

    /*
        Requisition New
    */

    public function renderRequisitionForm()
    {
        $user_id = Auth::user()->id;
        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();
        return view('requisitions.create-requisitions')->withBudgets($budgets)->withItems($items)->withData($data)->withAccounts($accounts);
    }



    /*

        New way to handle requisition
    */

    public function createRequisitionForm()
    {
        $user_id = Auth::user()->id;
        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();
        return view('requisition.create-requisition')->withBudgets($budgets)->withItems($items)->withData($data)->withAccounts($accounts);

    }

    public function getItemDescription($item_id)
    {
        $description = Item::where('id', $item_id)->select('items.description')->first();
        return response()->json(['result' => $description]);
        // return view('requisition.create-requisition')->withBudgets($budgets)->withItems($items)->withData($data)->withAccounts($accounts)->withDescription($description);
    }

    public function createRequisition(Request $request)
    {
        $this->validate(request(), [
            'budget_id',
            'item_id',
            'account_id',
            'user_id',
            'item_name',
            'description',
            'unit_measure',
            'unit_price',
            'quantity',
            'vat',
            'vat_amount',
            'gross_amount',
        ]);

        $user_id = Auth::user()->id;

        $vatAmountInclusive = (($request->quantity * $request->unit_price / 1.18) * 0.18);
        $vatAmountExclusive = (($request->quantity * $request->unit_price * 0.18));
        $vatAmountNonVat = 0;

        $grossAmountNovVat = ($request->quantity * $request->unit_price);
        $grossAmountVatInclusive = ($request->quantity * $request->unit_price);
        $grossAmountVatExclusive = ($request->quantity * $request->unit_price * 1.18);

        $pending_requisition = new RequisitionTemporaryTable();
        $getLatestReqNo = Requisition::select('req_no')->latest()->first();

        if (Requisition::select('req_no')->latest()->first() == null) {
            $req_no = 'Req-1';
        }elseif(Requisition::select('req_no')->latest()->first() != null) {
            $getLatestReqNo = Requisition::select('req_no')->latest()->distinct('req_no')->count('req_no');
            $req_no = 'Req-'.($getLatestReqNo + 1);
        }

        $pending_requisition->req_no = $req_no;
        $pending_requisition->budget_id = $request->budget_id;
        $pending_requisition->item_id = $request->item_id;
        $pending_requisition->account_id = $request->account_id;
        $pending_requisition->user_id = $user_id;
        $pending_requisition->item_name = $request->item_name;
        $pending_requisition->description = $request->description;
        $pending_requisition->unit_measure = $request->unit_measure;
        $pending_requisition->unit_price = $request->unit_price;
        $pending_requisition->quantity = $request->quantity;
        $pending_requisition->vat = $request->vat;

        if ($request->vat == 'VAT Exclusive') {
            $pending_requisition->vat_amount = $vatAmountExclusive;
            $pending_requisition->gross_amount = $grossAmountVatExclusive;
        }elseif ($request->vat == 'VAT Inclusive') {
            $pending_requisition->vat_amount = $vatAmountInclusive;
            $pending_requisition->gross_amount = $grossAmountVatInclusive;

        }else{
           $pending_requisition->vat_amount = $vatAmountNonVat;
           $pending_requisition->gross_amount = $grossAmountNovVat;
        }

        $pending_requisition->save();

        //dd($pending_requisition);
        return redirect()->route('requisitions.index');
    }

    public function getRequisitionSummary($req_id)
    {
        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        $requisition = Requisition::where('id', $req_id)->first();
        // $user = User::where('id', Auth::user()->id)->first();
        $user = User::join('requisitions','requisitions.user_id','users.id')->where('requisitions.id',$req_id)->select('users.*')->first();
        $submitted_requisitions = Requisition::where('user_id', Auth::user()->id)->distinct('user_id')->get();
        $req_comments = Comment::where('req_id', $req_id)->first();
        return view('requisition.requisition-summary',compact('submitted_requisitions','req_comments'))->withRequisition($requisition)->withUser($user)->withBudgets($budgets)->withAccounts($accounts)->withItems($items);
    }

    public function addRequisitionForm()
    {
        $user_id = Auth::user()->id;
        $items = Item::all();
        $budgets = Budget::all();
        $accounts = Account::all();
        $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();
        $view =view('requisition.new-requisition-form')->withBudgets($budgets)->withItems($items)->withData($data)->withAccounts($accounts)->render();
        return response()->json(['result' => $view]);
    }

    public function editRequisition(Request $request, $req_id)
    {

        $vatAmountInclusive = (($request->quantity * $request->unit_price / 1.18) * 0.18);
        $vatAmountExclusive = (($request->quantity * $request->unit_price * 0.18));
        $vatAmountNonVat = 0;

        $grossAmountNovVat = ($request->quantity * $request->unit_price);
        $grossAmountVatInclusive = ($request->quantity * $request->unit_price);
        $grossAmountVatExclusive = ($request->quantity * $request->unit_price * 1.18);

        $requisition = Requisition::findOrFail($req_id);

        $requisition->budget_id = $request->budget_id;
        $requisition->item_id = $request->item_id;
        $requisition->account_id = $request->account_id;
        $requisition->user_id = Auth::user()->id;
        $requisition->item_name = $request->item_name;
        $requisition->description = $request->description;
        $requisition->unit_measure = $request->unit_measure;
        $requisition->unit_price = $request->unit_price;
        $requisition->quantity = $request->quantity;
        $requisition->vat = $request->vat;

        if ($request->vat == 'VAT Exclusive') {
            $requisition->vat_amount = $vatAmountExclusive;
            $requisition->gross_amount = $grossAmountVatExclusive;
        }elseif ($request->vat == 'VAT Inclusive') {
            $requisition->vat_amount = $vatAmountInclusive;
            $requisition->gross_amount = $grossAmountVatInclusive;

        }else{
           $requisition->vat_amount = $vatAmountNonVat;
           $requisition->gross_amount = $grossAmountNovVat;
        }

        // dd($requisition);
        $requisition->save();

        session()->flash('message', 'Requisition has being updated');
        return redirect()->back()->with('message');
    }

    public static function getDescription($item_id)
    {
        $item_description = Item::where('id', $item_id)->select('items.description')->first();
        return response()->json(['result' => $item_description->description]);
        return view('requisition.create-requisition', compact($item_description->description));
    }

    public static function getLatestSerialNo()
    {
        $serial_no = RequisitionTemporaryTable::select('serial_no')
                     ->distinct('req_no')
                     ->orderBy('serial_no','desc')
                     ->first();
        return $serial_no['serial_no'];
    }

    public function submit_requisition(Request $request)
    {
        $request->status = 'onprocess';
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

        if (Requisition::select('req_no')->latest()->first() == null)
        {
            $req_no = 'Req-1';
        }elseif(Requisition::select('req_no')->latest()->first() != null)
        {
            foreach(DB::table('requisitions')->select('req_no')->distinct('requsitions.user_id')->get() as $req_no)
            {
                $req_no = $req_no->req_no;
            }
        }
        else{
            $getLatestReqNo = Requisition::select('req_no')->latest()->distinct('req_no')->count('req_no');
            $req_no = 'Req-'.($getLatestReqNo + 1);
        }
        session()->put('forms.budget', $request->get('budget_id'));

        DB::table('requisition_temporary_tables')->insert(['req_no' => $request->req_no,'serial_no' => RequisitionsController::getLatestSerialNo() + 1,'budget_id' => $request->budget_id,'item_id' => $request->item_id,'account_id' => $request->account_id, 'user_id' => $request->user_id,'activity_name' => $request->activity_name, 'item_name' => $request->item_name2, 'unit_measure' => $request->unit_measure, 'quantity' => $request->quantity, 'unit_price' => $request->unit_price,
            'vat' => $request->vat, 'description' => $request->description, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount, 'status' => $request->status , 'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);

        if ($request->budget_id != 0) {
          $data = DB::table('requisition_temporary_tables')->join('budgets','requisition_temporary_tables.budget_id','budgets.id')->join('items','requisition_temporary_tables.item_id','items.id')->join('accounts','requisition_temporary_tables.account_id','accounts.id')->select('requisition_temporary_tables.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')->where('req_no', $request->req_no)->get();

          $view = view('requisition.render-requisition')->with('data', $data)->render();
        }elseif($request->budget_id == 0){
          $data = DB::table('requisition_temporary_tables')->join('accounts','requisition_temporary_tables.account_id','accounts.id')->select('requisition_temporary_tables.*','accounts.account_name as account')->where('req_no', $request->req_no)->get();

          $view = view('requisition.render-requisition')->with('data', $data)->render();
        }

        //return view('requisition.render-requisition')->with('data', $data)->withInput()->render();

        return response()->json(['result' => $view]);
    }

    public static function getLatestReqNoCount()
    {
        return Requisition::select('req_no')->latest()->distinct('req_no')->count('req_no');
    }

    public static function getLatestReqNo()
    {
        return Requisition::select('req_no')->latest()->first();
    }

    public static function getTheLatestRequisitionNumber()
    {
        if (RequisitionsController::getLatestReqNo() == null)
        {
            $req_no = 'Req-1';
        }elseif(RequisitionsController::getLatestReqNo() != null) {
            //$getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            // $req_no = 'Req-'.(RequisitionsController::getLatestReqNoCount() + 1);
            foreach(DB::table('requisitions')->select('req_no')->distinct('requsitions.user_id')->get() as $req_no){
                $req_no = $req_no->req_no;
            }
        }else{
            $req_no = 'Req-'.(RequisitionsController::getLatestReqNoCount() + 1);
        }

        return $req_no;
    }

    public function permanentRequisitionSubmission($req_no)
    {
        $requisitions = RequisitionTemporaryTable::where('req_no', $req_no)->where('user_id', Auth::user()->id)->get();

        foreach ($requisitions as $requisition) {
            DB::table('requisitions')->insert([
                'budget_id' => $requisition->budget_id,
                'item_id' => $requisition->item_id,
                'account_id' => $requisition->account_id,
                'user_id' => $requisition->user_id,
                'req_no' => $requisition->req_no,
                'serial_no' => $requisition->serial_no,
                'activity_name' => $requisition->activity_name,
                'item_name' => $requisition->item_name,
                'description' => $requisition->description,
                'unit_measure' => $requisition->unit_measure,
                'quantity' => $requisition->quantity,
                'unit_price' => $requisition->unit_price,
                'vat' => $requisition->vat,
                'vat_amount' => $requisition->vat_amount,
                'gross_amount' =>$requisition->gross_amount,
                'status' => $requisition->status,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        RequisitionTemporaryTable::truncate();

        session()->flash('message', 'Retirement has being created');
        return redirect()->route('submitted-requisitions');
    }

    public static function getRequisitionTotal($req_no)
    {
        return Requisition::where('req_no',$req_no)->sum('gross_amount');
    }

    public function getAllPaidRequisition($req_no)
    {
        $submitted_requisitions = Requisition::where('req_no', $req_no)
                                  ->where('requisitions.status', 'Paid')
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->join('items','requisitions.item_id','items.id')
                                  ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                                  ->get();

        $submitted_paid_no_budget = Requisition::where('requisitions.status','Paid')->where('user_id',Auth::user()->id)->where('budget_id',0)->get();

        return view('retirements.all-retirements', compact('submitted_requisitions','req_no','submitted_paid_no_budget'));
    }

    public function unretiredRequisition()
    {
        $unretired_requisition = Requisition::join('users','requisitions.user_id','users.id')
                                 ->where('requisitions.status', 'Paid')
                                 ->select('requisitions.req_no','users.username as username')
                                 ->get();

        return view('requisition.unretired-requisitions', compact('unretired_requisition'));
    }

    public function deleteRecord()
    {
        if (isset($_POST['id'])) {
           foreach($_POST['id'] as $id){
              $record = DB::table('requisition_temporary_tables')->where('id', $id)->delete();
              return redirect()->back();
           }
        }
    }

}
