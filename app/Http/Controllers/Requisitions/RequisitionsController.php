<?php

namespace App\Http\Controllers\Requisitions;

use DB;
use PDF;
use Dompdf\Options;
use Alert;
use Session;
use App\User;
use App\Item\Item;
use App\Limits\Limit;
use App\Budget\Budget;
use App\Accounts\Account;
use App\Accounts\SubAccountType;
use App\Comments\Comment;
use Illuminate\Http\Request;
use App\StaffLevel\StaffLevel;
use App\Department\Department;
use Illuminate\Support\Carbon;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\EditRequisitionTemporaryTable;
use App\Accounts\FinanceSupportiveDetail;
use App\Temporary\RequisitionTemporaryTable;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Accounts\FinanceSupportiveDetailsController;

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

        $limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
        $limitNormalStaff = Limit::where('stafflevel_id',$normalStaff);
        $ceoLimit = Limit::where('stafflevel_id',$ceo)->select('max_amount')->first();
        $limitSupervisor = Limit::where('stafflevel_id',$supervisor)->select('max_amount')->first();

        $user_dept = User::join('departments','users.department_id','departments.id')
                          ->where('departments.id', Auth::user()->department_id)
                          ->select('users.department_id as dept_id')
                          ->distinct('dept_id')
                          ->first();

        if(Auth::user()->stafflevel_id == $normalStaff)
        {

            $pending_requisitions = Requisition::join('users','requisitions.user_id','users.id')
                                               ->join('departments','users.department_id','departments.id')
                                               ->join('budgets','requisitions.budget_id','budgets.id')
                                               ->where('budgets.is_active', 'Active')
                                               ->select('requisitions.req_no','users.username as username','departments.name as department')

                                               ->where('requisitions.user_id', Auth::user()->id)
                                               ->where('requisitions.status', 'Rejected')
                                               ->where('departments.status', 'Active')
                                               ->whereIn('users.stafflevel_id', [$normalStaff])
                                               ->whereNotIn('users.stafflevel_id', [$supervisor,$hod,$ceo,$financeDirector])

                                               // ->orWhere('requisitions.status', 'onprocess')

                                               ->orWhere('requisitions.status', 'Rejected By Supervisor')
                                               ->orWhere('requisitions.status', 'Rejected By HOD')
                                               ->orWhere('requisitions.status', 'Rejected By CEO')
                                               // ->orWhere('requisitions.status', 'Rejected')

                                               ->groupBy('requisitions.req_no')
                                               ->get();

            return view('requisitions.view-requisitions', compact('pending_requisitions','stafflevels'))->withUser($user);
        }elseif(Auth::user()->stafflevel_id == $supervisor)
        {


            $pending_requisitions = Requisition::join('users','requisitions.user_id','users.id')
                                            ->join('departments','users.department_id','departments.id')
                                            ->join('budgets','requisitions.budget_id','budgets.id')
                                            ->where('budgets.is_active', 'Active')
                                            ->select('requisitions.req_no','users.username as username','departments.name as department')
                                            // ->whereBetween('requisitions.gross_amount',  [0, $limitSupervisor->max_amount])
                                            ->where('departments.status', 'Active')
                                            ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor])
                                            ->whereNotIn('users.stafflevel_id', [$hod,$ceo,$financeDirector])
                                            // ->where('requisitions.gross_amount', '<=', $limitHOD->max_amount)
                                            // ->where('requisitions.gross_amount', '>=', $limitSupervisor->max_amount)
                                            ->where('users.department_id', $user_dept->dept_id)
                                            ->where('requisitions.status', 'onprocess')
                                            ->orWhere('requisitions.status', 'Rejected By Supervisor')
                                            ->orWhere('requisitions.status', 'Rejected By HOD')
                                            ->orWhere('requisitions.status', 'Rejected By CEO')
                                            ->orWhere('requisitions.status', 'Rejected')
                                            ->orWhere('requisitions.status', 'Rejected By Supervisor')
                                            ->groupBy('requisitions.req_no')

                                            // ->where('requisitions.gross_amount', '<=', $limitSupervisor->max_amount)
                                            ->get();

            return view('requisitions.view-requisitions', compact('pending_requisitions','stafflevels'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $hod)
        {


            $pending_requisitions = Requisition::join('users','requisitions.user_id','users.id')
                                            ->join('departments','users.department_id','departments.id')
                                            ->join('budgets','requisitions.budget_id','budgets.id')
                                            ->where('budgets.is_active', 'Active')
                                            ->select('requisitions.req_no','users.username as username','departments.name as department')

                                            ->where('requisitions.gross_amount', '>=', $limitSupervisor->max_amount)
                                            ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor, $hod])
                                            ->where('users.department_id', $user_dept->dept_id)
                                            // ->whereNotIn('users.stafflevel_id', [$ceo,$financeDirector])
                                            // ->where('requisitions.status', 'onprocess')
                                            ->where('requisitions.status', 'Approved By Supervisor')
                                            // ->whereBetween('requisitions.gross_amount', [$limitSupervisor->max_amount, $limitHOD->max_amount])

                                            ->where('departments.status', 'Active')
                                            ->orWhere('requisitions.status', 'onprocess supervisor')
                                            // ->orWhere('requisitions.status', 'Rejected By Supervisor')
                                            // ->orWhere('requisitions.status', 'Rejected By HOD')
                                            ->orWhere('requisitions.status', 'Rejected By CEO')
                                            // ->orWhere('requisitions.status', 'Rejected')
                                            ->orWhere('requisitions.status', 'Rejected')
                                            ->orWhere('requisitions.status', 'Rejected By CEO')
                                            ->groupBy('requisitions.req_no')
                                            ->get();

            return view('requisitions.view-requisitions', compact('pending_requisitions','stafflevels'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $ceo)
        {


            $pending_requisitions = Requisition::join('users','requisitions.user_id','users.id')
                                               ->join('departments','users.department_id','departments.id')
                                               ->join('budgets','requisitions.budget_id','budgets.id')
                                               ->where('budgets.is_active', 'Active')
                                               ->select('requisitions.req_no','users.username as username','departments.name as department')
                                               ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor, $hod, $ceo,$financeDirector])
                                               ->whereBetween('requisitions.gross_amount', [$limitHOD->max_amount, 100000000000])
                                            //    ->where('requisitions.status', 'onprocess')
                                            //    ->where('requisitions.gross_amount', '>=', $ceoLimit)
                                               ->where('requisitions.status', 'onprocess hod')
                                               ->where('departments.status', 'Active')
                                               ->orWhere('requisitions.status', 'onprocess finance')
                                               // ->orWhere('requisitions.status', 'Rejected By Supervisor')
                                               // ->orWhere('requisitions.status', 'Rejected By HOD')
                                               // ->orWhere('requisitions.status', 'Rejected By CEO')
                                               // ->orWhere('requisitions.status', 'Rejected')
                                               // ->orWhere('requisitions.status', 'Rejected')
                                               ->orWhere('requisitions.status', 'Approved By Finance')
                                               ->groupBy('requisitions.req_no')
                                               ->get();

            return view('requisitions.view-requisitions', compact('pending_requisitions','stafflevels'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $financeDirector)
        {


            $pending_requisitions = Requisition::join('users','requisitions.user_id','users.id')
                                               ->join('departments','users.department_id','departments.id')
                                               ->join('budgets','requisitions.budget_id','budgets.id')
                                               ->where('budgets.is_active', 'Active')
                                               ->select('requisitions.req_no','users.username as username','departments.name as department')
                                               ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor, $hod, $ceo, $financeDirector])
                                               // ->where('requisitions.status', 'onprocess')
                                               // ->orWhere('requisitions.status', 'Rejected By Supervisor')
                                               // ->orWhere('requisitions.status', 'Rejected By HOD')
                                               ->where('requisitions.status', 'Approved By Supervisor')
                                               ->where('departments.status', 'Active')
                                               ->orWhere('requisitions.status', 'onprocess hod')
                                               ->orWhere('requisitions.status', 'onprocess ceo')
                                               ->orWhere('requisitions.status', 'Rejected By CEO')
                                               // ->orWhere('requisitions.status', 'Rejected')
                                               // ->orWhere('requisitions.status', 'Rejected')
                                               ->orWhere('requisitions.status', 'Approved By CEO')
                                               ->orWhere('requisitions.status', 'Approved By HOD')
                                               ->groupBy('requisitions.req_no')
                                               ->get();

            return view('requisitions.view-requisitions', compact('pending_requisitions','stafflevels'))->withUser($user);
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
        $budgets = Budget::where('budgets.status', 'Confirmed')->where('is_active', 'Active')->get();
        $accounts = Account::all();
        $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();
        $activity_name = RequisitionTemporaryTable::where('user_id', Auth::user()->id)->select('activity_name')->first();

        return view('requisitions.create-requisition', compact('activity_name'))->withBudgets($budgets)->withItems($items)->withData($data)->withAccounts($accounts);

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
    // public function edit($id)
    // {
    //     $requisition = Requisition::where('id', $id)->first();
    //     if ($requisition->status == 'Confirmed' || $requisition->status == 'Paid') {
    //         alert()->success('Already approved, cannot edit', 'Opps!');
    //         return redirect()->back();
    //     }
    //     $submitted_requisitions = Requisition::where('requisitions.id', $id)
    //                             ->join('budgets','requisitions.budget_id','budgets.id')
    //                             ->join('items','requisitions.item_id','items.id')
    //                             ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
    //                             ->select('requisitions.*','budgets.title as budget','items.item_name as item','finance_supportive_details.amount_paid as paid')
    //                             ->distinct('req_no')
    //                             ->get();

    //     $user_id = Auth::user()->id;
    //     $items = Item::where('budget_id', $requisition->budget_id)->get();
    //     $budgets = Budget::all();
    //     $accounts = Account::all();
    //     $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();
    //     $req_id = Requisition::findOrFail($id);
    //     $req_no = $req_id->req_no;
    //     $requisition = Requisition::where('id',$id)->first();
    //     return view('requisition.edit-requisition', compact('req_id','req_no','user_id','items','budgets','accounts','requisition','submitted_requisitions','id'));
    // }

    public function editRequisitionByReqNo($req_no)
    {
        $requisition = Requisition::where('req_no', $req_no)->where('status', '!=', 'Edited')->where('status', '!=', 'Deleted')->get();
        foreach($requisition as $requisition){
            if ($requisition->status == 'Confirmed' || $requisition->status == 'Paid') {
                alert()->success('Already approved, cannot edit', 'Opps!');
                return redirect()->back();
            }
        }

        // $submitted_requisitions = Requisition::where('requisitions.req_no', $req_no)
        //                         ->join('budgets','requisitions.budget_id','budgets.id')
        //                         ->join('items','requisitions.item_id','items.id')
        //                         ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
        //                         ->select('requisitions.*','budgets.title as budget','items.item_name as item','finance_supportive_details.amount_paid as paid')
        //                         ->distinct('req_no')
        //                         ->get();

        $user_id = Auth::user()->id;
        $items = Item::where('budget_id', $requisition->budget_id)->get();
        $budget_id = $requisition->budget_id;
        $accounts = Account::all();
        // $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();
        // $req_id = Requisition::findOrFail($req_no);
        // $req_no = $req_id->req_no;
        // $requisition = Requisition::where('req_no',$req_no)->first();

        return view('requisition.new-edit-requisition', compact('items', 'accounts', 'budget_id', 'req_no', 'requisition'));
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
                'budget_id' => $requisition->budget_id,
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
        $budgets = Budget::where('budgets.status', 'Confirmed')->get();
        $accounts = Account::all();
        $view = view('requisitions.new-requisition-row')->withBudgets($budgets)->withItems($items)->withAccounts($accounts)->render();
        return response()->json(['result' => $view]);
    }

    public function submit_single_row($budget, $item, $accounts) {

        if($budget != 0)
        {
            Validator::make(Input::name('item_id'), [
                'item_id' => 'required',
            ]);
        }

        $user_id = Auth::user()->id;
        $items = Item::all();
        $budgets = Budget::where('budgets.status', 'Confirmed')->get();
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

        $limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
        $limitSupervisor = Limit::where('stafflevel_id',$supervisor)->select('max_amount')->first();
        $limitNormalStaff = Limit::where('stafflevel_id',$normalStaff);


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
            $limitSupervisor = Limit::where('stafflevel_id',$supervisor)->select('max_amount')->first();
            $limitNormalStaff = Limit::where('stafflevel_id',$normalStaff)
                                ->select('max_amount')->first();

                                $submitted_requisitions = Requisition::join('users','users.id','requisitions.user_id')
                                                        ->join('departments','users.department_id','departments.id')
                                                        ->join('budgets','requisitions.budget_id','budgets.id')
                                                        ->where('budgets.is_active', 'Active')
                                                        ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                                        ->where('departments.id',$user_dept->dept_id)
                                                        // ->where('users.stafflevel_id','!=',[$ceo])
                                                        // ->whereBetween('requisitions.gross_amount', [0,$limitSupervisor->max_amount])
                                                        ->whereIn('users.stafflevel_id',[$normalStaff, $supervisor, $hod])
                                                        // ->where('requisitions.status', 'Approved By Finance')
                                                        ->where('requisitions.status', 'onprocess hod')
                                                        // ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                                        // ->orWhere('requisitions.status', 'like', '%Paid%')
                                                    //   ->orWhere('requisitions.status', 'like', '%onprocess%')
                                                        // ->where('requisitions.gross_amount','>',$limitNormalStaff->max_amount)
                                                        // ->whereBetween('requisitions.gross_amount', [$limitSupervisor->max_amount, $limitHOD->max_amount])
                                                        // ->where('requisitions.gross_amount', '<=', $limitHOD->max_amount)
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
            $limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();

            $submitted_requisitions = Requisition::join('users','users.id','requisitions.user_id')
                                      ->join('departments','users.department_id','departments.id')
                                      ->join('budgets','requisitions.budget_id','budgets.id')
                                      ->where('budgets.is_active', 'Active')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')

                                      // ->whereBetween('requisitions.gross_amount', [0,$limitSupervisor->max_amount])
                                      ->whereIn('users.stafflevel_id',[$normalStaff, $supervisor])
                                      ->whereNotIn('users.stafflevel_id', [$hod,$ceo,$financeDirector])
                                      ->where('departments.id',$user_dept->dept_id)
                                      ->where('users.stafflevel_id','!=',[$hod])
                                      ->where('requisitions.gross_amount', '<=', $limitHOD->max_amount)
                                      ->where('requisitions.gross_amount', '>=', $limitSupervisor->max_amount)
                                      ->where('requisitions.status', 'Approved By Supervisor')
                                      ->orWhere('requisitions.status', 'onprocess supervisor')

                                      // ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      // ->orWhere('requisitions.status', 'like', '%Paid%')
                                    //   ->orWhere('requisitions.status', 'like', '%onprocess%')
                                    //   ->where('requisitions.gross_amount','>',$limitSupervisor->max_amount)
                                    //   ->whereBetween('requisitions.gross_amount', [0, $limitSupervisor->max_amount])
                                    //   ->where('requisitions.gross_amount', '<=', $limitSupervisor->max_amount)
                                      ->distinct('req_no')
                                      ->get();
            return view('requisitions.supervisor-requisitions', compact('submitted_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $ceo)
        {

            $hodLimit = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
            $limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
            $ceoLimit = Limit::where('stafflevel_id',$ceo)->select('max_amount')->first();

            $submitted_requisitions = Requisition::select('user_id')
                                      ->join('users','users.id','requisitions.user_id')
                                      ->join('departments','users.department_id','departments.id')
                                      ->join('budgets','requisitions.budget_id','budgets.id')
                                      ->where('budgets.is_active', 'Active')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')

                                      // ->orWhere('requisitions.status', 'like', '%Confirmed%')
                                      // ->orWhere('requisitions.status', 'like', '%Paid%')
                                    //   ->orWhere('requisitions.status', 'like', '%onprocess%')
                                      ->whereIn('users.stafflevel_id', [$hod,$financeDirector,$normalStaff,$ceo,$supervisor])
                                      ->where('requisitions.status', 'onprocess ceo')
                                      ->orWhere('requisitions.status', 'Approved By CEO')
                                      // ->orWhere('users.stafflevel_id', ['4','3'])
                                      ->whereBetween('requisitions.gross_amount', [$limitHOD->max_amount, 1000000000000000])

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
                                      ->join('budgets','requisitions.budget_id','budgets.id')
                                      ->where('budgets.is_active', 'Active')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                      ->where('users.id', Auth::user()->id)
                                      ->where('departments.id',$user_dept->dept_id)
                                      ->where('requisitions.status', 'onprocess')
                                      ->where('departments.status', 'Active')
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
                                      ->join('budgets','requisitions.budget_id','budgets.id')
                                      ->where('budgets.is_active', 'Active')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                      // ->where('requisitions.status', 'Confirmed')
                                      ->orWhere('requisitions.status', 'onprocess finance')
                                    //   ->orWhere('requisitions.status', 'Approved By HOD')
                                    //   ->orWhere('requisitions.status', 'onprocess')
                                    //   ->orWhere('requisitions.status', 'Approved By Supervisor')
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
                                      ->join('budgets','requisitions.budget_id','budgets.id')
                                      ->where('budgets.is_active', 'Active')
                                      ->where('departments.name','Finance')
                                      ->select('requisitions.req_no','users.*','user_id','users.username as username','departments.name as department')
                                      ->where('requisitions.status', 'Approved By CEO')
                                      ->orWhere('requisitions.status', 'Approved By HOD')
                                      ->orWhere('requisitions.status', 'onprocess')
                                      ->orWhere('requisitions.status', 'Approved By Supervisor')
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
                                  ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                                  ->where('requisitions.status', '!=', 'Deleted')
                                  ->where('requisitions.status', '!=', 'Edited')
                                  ->distinct('req_no')
                                  ->get();

        $amount_retired = Retirement::where('req_no', $req_no)->where('status', '!=', 'Edited')->sum('gross_amount');
        $amount_requested = Requisition::where('requisitions.status','!=','Edited')->where('status','!=','Deleted')->where('req_no', $req_no)->sum('gross_amount');
        $amount_paid = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $req_no)->where('status', 'Pay')->sum('amount_paid');
        $amount_received = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $req_no)->where('status', 'Receive')->sum('amount_paid');
        $amount_returned = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $req_no)->where('status', 'Return')->sum('amount_paid');
        $amount_unretired = $amount_paid - ($amount_retired + $amount_received + $amount_returned);
        $amount_claimed = ($amount_retired + $amount_received) - $amount_paid;

        return view('requisitions.all-requisitions', compact('amount_paid','amount_retired','amount_unretired','submitted_requisitions','req_no'));
    }

    public static function getAmountPaid($req_no)
    {
        return FinanceSupportiveDetail::where('req_no',$req_no)->sum('amount_paid');
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

        $userSessionID = Session::getId();
        $stafflevel_id = StaffLevel::where('id', Auth::user()->id)->select('staff_levels.id')->first();

        $requisitionID = Requisition::where('req_no',$requisition_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->first();
        $requisitionUserID = Requisition::where('requisitions.user_id', Auth::user()->id)->first();
        $requisitionTotal = Requisition::where('requisitions.req_no', $requisition_no)
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->select('requisitions.gross_amount','requisitions.gross_amount')
                                  ->where('requisitions.status', '!=', 'Deleted')
                                  ->where('requisitions.status', '!=', 'Edited')
                                  ->distinct('user_id')
                                  ->get();

        $amount_paid = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $requisition_no)->where('status', 'Pay')->sum('amount_paid');
        $amount_received = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $requisition_no)->where('status', 'Receive')->sum('amount_paid');
        $amount_returned = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $requisition_no)->where('status', 'Return')->sum('amount_paid');
        $paid_amount = $amount_paid + $amount_returned;

        $budgetTotal = Budget::join('items','items.budget_id','budgets.id')
                             ->join('requisitions','requisitions.budget_id','budgets.id')
                             ->where('requisitions.req_no',$requisition_no)
                             ->sum('items.total');

        $requisition_total_amount = Requisition::where('req_no', $requisition_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->sum('gross_amount');

        $user_id = Auth::user()->id;
        $user_staff_level = Requisition::join('users','requisitions.user_id','users.id')->join('staff_levels','users.stafflevel_id','staff_levels.id')->select('users.stafflevel_id')->where('req_no', $requisition_no)->first();

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
                return redirect(url('/pending-requisitions'));
              }



              $result = DB::table('requisitions')->where('req_no', $requisition_no)->where('status','!=','Deleted')->where('status','!=','Edited')->update([
                  'status' => "Confirmed",
                  'approver_id' => Auth::user()->stafflevel_id,
              ]);
              alert()->success('Requisition approved successful', 'Confirmed');
              return redirect(url('/pending-requisitions'));

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

              $result = DB::table('requisitions')->where('req_no', $requisition_no)->where('status','!=','Deleted')->where('status','!=','Edited')->update([
                  'status' => "Approved By Supervisor",
                  'approver_id' => Auth::user()->stafflevel_id,
              ]);
              alert()->success('Requisition approved successful', 'Confirmed');
              return redirect(url('/pending-requisitions'));
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
                 return redirect(url('/pending-requisitions'));
              }

              $result = DB::table('requisitions')->where('req_no', $requisition_no)->where('status','!=','Deleted')->where('status','!=','Edited')->update([
                  'status' => "Approved By HOD",
                  'approver_id' => Auth::user()->stafflevel_id,
              ]);
              alert()->success('Requisition approved successful', 'Confirmed');
              return redirect(url('/pending-requisitions'));


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

              $accounts = Account::where('sub_account_type', 3)->get();

              if (Requisition::where('req_no',$requisition_no)->where('status', '!=', 'Deleted')->where('status','!=','Edited')->sum('gross_amount') == FinanceSupportiveDetail::where('req_no',$requisitionID->req_no)->sum('amount_paid')) {
                  alert()->error('Opps! You cannot pay more than requested amount', 'Error')->persistent('Close');
                  return redirect(url('submitted-requisitions/'.$requisition_no));
              }

              if ($requisitionID->status == 'Paid' || $requisitionID->status == 'Confirmed') {
                  return view('requisition.finance-supportive-details', compact('paid_amount','requisitionID','financeStaffs','accounts','requisition_no'));
              }

              if($requisition_total_amount >= $ceoLimit->max_amount && $user_staff_level->stafflevel_id != $ceo || $user_staff_level->stafflevel_id == $hod){
                  $result = DB::table('requisitions')->join('users','requisitions.user_id','users.id')->where('req_no', $requisition_no)->where('requisitions.status','!=','Deleted')->where('requisitions.status','!=','Edited')->where('users.stafflevel_id', '!=', $ceo)->update([
                      'requisitions.status' => "Approved By Finance",
                      'approver_id' => Auth::user()->stafflevel_id,
                  ]);
              }else{
                  $result = DB::table('requisitions')->where('req_no', $requisition_no)->where('status','!=','Deleted')->where('status','!=','Edited')->update([
                      'status' => "Confirmed",
                      'approver_id' => Auth::user()->stafflevel_id,
                  ]);
              }



            //   alert()->success('Requisition approved successful', 'Good Job');
              if ($requisitionID->status == 'Confirmed') {

                  return view('requisition.finance-supportive-details', compact('requisitionID','financeStaffs','accounts','requisition_no'));
              }else{

              }

              return redirect(url('/pending-requisitions'));


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

    public function processReceptsAndBalance($req_no)
    {
        $requisitionID = Requisition::where('req_no',$req_no)->first();
        $financeStaffs = User::join('departments','departments.id','users.department_id')
                             ->where('departments.name','Finance')
                             ->where('users.username', '!=', 'CEO')
                             ->get();
        $account_sub_type = SubAccountType::where('account_subtype_name', 'Bank Accounts')->pluck('id')->first();
        $accounts = Account::where('sub_account_type', $account_sub_type)->get();
        $requisitions = Requisition::all();
        $amount_retired = Retirement::where('req_no', $req_no)->where('status', '!=', 'Edited')->sum('gross_amount');
        $amount_requested = Requisition::where('requisitions.req_no', $req_no)->where('status','!=','Deleted')->where('status','!=','Edited')->sum('requisitions.gross_amount');
        $amount_paid = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $req_no)->where('status', 'Pay')->sum('amount_paid');
        $amount_received = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $req_no)->where('status', 'Receive')->sum('amount_paid');
        $amount_returned = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $req_no)->where('status', 'Return')->sum('amount_paid');
        // $amount_unretired = $amount_paid - ($amount_retired + $amount_received + $amount_returned);
        $paid_amount = $amount_paid + $amount_returned;
        $amount_claimed = ($amount_retired + $amount_received) - $paid_amount;
        $retired_amount = $amount_retired + $amount_received;
        $amount_unretired = $paid_amount - $retired_amount;
        return view('requisition.return-receive-payments', compact('retired_amount','amount_returned','amount_received','amount_claimed','amount_unretired','amount_retired','amount_requested','paid_amount','requisitionID','financeStaffs','accounts','req_no','requisitions'));
    }

    public function rejectRequisition($requisition_no)
    {

        $items = Item::all();
        $budgets = Budget::where('budgets.status', 'Confirmed')->get();
        $accounts = Account::all();
        $userSessionID = Session::getId();
        $stafflevel_id = StaffLevel::where('id', Auth::user()->id)->select('staff_levels.id')->first();
        $requisitionID = Requisition::where('req_no',$requisition_no)->first();
        $requisitionUserID = Requisition::where('requisitions.user_id', Auth::user()->id)->first();
        $requisitionTotal = Requisition::where('requisitions.req_no', $requisition_no)
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->select('requisitions.gross_amount','requisitions.gross_amount')
                                  ->where('budgets.status', 'Confirmed')
                                  ->distinct('user_id')
                                  ->first();

        $budgetTotal = Budget::join('items','items.budget_id','budgets.id')
                             ->join('requisitions','requisitions.budget_id','budgets.id')
                             ->where('requisitions.id',$requisition_no)
                             ->where('budgets.status', 'Confirmed')
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

                if($requisitionID->status == 'Paid')
                {
                    alert()->error('Already paid, cannot reject', 'Ooops');
                    return redirect()->back();
                }

                $result = DB::table('requisitions')->where('req_no', $requisition_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->update([
                  'status' => "Rejected By CEO",
                  'approver_id' => Auth::user()->stafflevel_id,
                ]);
                return view('requisition.requisition-comments', compact('requisition_no'));

          }elseif (Auth::user()->stafflevel_id == 3)
          {
                $stafflevel_id = Auth::user()->stafflevel_id;
                $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                            ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                            ->select('users.username as username')
                            ->first();

                if($requisitionID->status == 'Paid')
                {
                    alert()->error('Already paid, cannot reject', 'Ooops');
                    return redirect()->back();
                }

                $result = DB::table('requisitions')->where('req_no', $requisition_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->update([
                    'status' => "Rejected By Supervisor",
                    'approver_id' => Auth::user()->stafflevel_id,
                ]);
                return view('requisition.requisition-comments', compact('requisition_no'));
          }elseif (Auth::user()->stafflevel_id == 1)
          {
                $stafflevel_id = Auth::user()->stafflevel_id;
                $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                            ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                            ->select('users.username as username')
                            ->first();

                if($requisitionID->status == 'Paid')
                {
                    alert()->error('Already paid, cannot reject', 'Ooops');
                    return redirect()->back();
                }

                $result = DB::table('requisitions')->where('req_no', $requisition_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->update([
                    'status' => "Rejected By HOD",
                    'approver_id' => Auth::user()->stafflevel_id,
                ]);

                return view('requisition.requisition-comments', compact('requisition_no'));


          }elseif (Auth::user()->stafflevel_id == 5)
          {
                $stafflevel_id = Auth::user()->stafflevel_id;
                $approver = StaffLevel::join('users','staff_levels.id','users.stafflevel_id')
                          ->where('users.id',Auth::user()->id)->where('users.stafflevel_id',$stafflevel_id)
                          ->select('users.username as username')
                          ->first();

                if($requisitionID->status == 'Paid')
                {
                    alert()->error('Already paid, cannot reject', 'Ooops');
                    return redirect()->back();
                }

                $result = DB::table('requisitions')->where('req_no', $requisition_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->update([
                  'status' => "Rejected",
                  'approver_id' => Auth::user()->stafflevel_id,
                ]);

                return view('requisition.requisition-comments', compact('requisition_no'));

          }

    }

    public function approvedRequisitions()
    {
        // $approved_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no, requisitions.status
        //                           FROM `requisitions`
        //                           JOIN users on requisitions.user_id = users.id
        //                           JOIN departments on users.department_id = departments.id
        //                           WHERE requisitions.status
        //                           = 'Confirmed' GROUP BY requisitions.req_no"));

        $user = User::where('id', Auth::user()->id)->first();
        $requisition = Requisition::where('user_id', Auth::user()->id)->first();
        $departments = Department::all();
        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;

        $user_dept = User::join('departments','users.department_id','departments.id')
                          ->where('departments.id', Auth::user()->department_id)
                          ->select('users.department_id as dept_id')
                          ->distinct('dept_id')
                          ->first();

        if(Auth::user()->stafflevel_id == $normalStaff){
            $approved_requisitions = Requisition::join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->join('budgets','requisitions.budget_id','budgets.id')
                                 ->where('budgets.is_active', 'Active')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 ->whereIn('users.stafflevel_id', [$normalStaff])
                                 ->where('users.department_id', $user_dept->dept_id)
                                 ->where('departments.status', 'Active')
                                 ->where('requisitions.status', 'Confirmed')
                                 ->where('users.id', Auth::user()->id)
                                 // ->where('users.department_id', 'departments.id')
                                 // ->orWhere('requisitions.status', 'Approved By Supervisor')
                                 // ->orWhere('requisitions.status', 'Approved By HOD')
                                 // ->orWhere('requisitions.status', 'Approved By Finance')
                                 ->distinct('requisitions.req_no')
                                 ->get();
            return view('requisition.approved-requisitions', compact('approved_requisitions'));
        }elseif(Auth::user()->stafflevel_id == $supervisor){
            $approved_requisitions = Requisition::join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->join('budgets','requisitions.budget_id','budgets.id')
                                 ->where('budgets.is_active', 'Active')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 // ->where('users.department_id', 'departments.id')
                                 ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor])
                                 ->where('users.department_id', $user_dept->dept_id)
                                 ->where('requisitions.status', '!=', 'Deleted')
                                 ->where('requisitions.status', '!=', 'Edited')
                                 ->where('departments.status', 'Active')
                                 ->where('requisitions.status', 'Confirmed')
                                 // ->orWhere('requisitions.status', 'Approved By Supervisor')
                                 // ->orWhere('requisitions.status', 'Approved By HOD')
                                 // ->orWhere('requisitions.status', 'Approved By Finance')
                                 ->distinct('requisitions.req_no')
                                 ->get();
            return view('requisition.approved-requisitions', compact('approved_requisitions'));
        }elseif(Auth::user()->stafflevel_id == $hod){
            $approved_requisitions = Requisition::join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->join('budgets','requisitions.budget_id','budgets.id')
                                 ->where('budgets.is_active', 'Active')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor, $hod])
                                 ->where('users.department_id', $user_dept->dept_id)
                                 ->where('requisitions.status', '!=', 'Deleted')
                                 ->where('requisitions.status', '!=', 'Edited')
                                 ->where('departments.status', 'Active')
                                 ->where('requisitions.status', 'Confirmed')
                                 // ->orWhere('requisitions.status', 'Approved By HOD')
                                 // ->orWhere('requisitions.status', 'Approved By Finance')
                                 ->distinct('requisitions.req_no')
                                 ->get();
            return view('requisition.approved-requisitions', compact('approved_requisitions'));
        }elseif(Auth::user()->stafflevel_id == $ceo || Auth::user()->stafflevel_id == $financeDirector){
            $approved_requisitions = Requisition::join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->join('budgets','requisitions.budget_id','budgets.id')
                                 ->where('budgets.is_active', 'Active')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor, $hod, $ceo, $financeDirector])
                                 ->where('requisitions.status', '!=', 'Deleted')
                                 ->where('requisitions.status', '!=', 'Edited')
                                 ->where('requisitions.status', 'Confirmed')
                                 ->where('departments.status', 'Active')
                                 ->distinct('requisitions.req_no')
                                 ->get();
            return view('requisition.approved-requisitions', compact('approved_requisitions'));
        }

        // return view('requisition.approved-requisitions', compact('approved_requisitions'));
    }

    public function paidRequisitions()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $requisition = Requisition::where('user_id', Auth::user()->id)->first();
        $departments = Department::all();
        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;

        $user_dept = User::join('departments','users.department_id','departments.id')
                          ->where('departments.id', Auth::user()->department_id)
                          ->select('users.department_id as dept_id')
                          ->distinct('dept_id')
                          ->first();

        if(Auth::user()->stafflevel_id == $normalStaff){
            $paid_requisitions = Requisition::where('requisitions.status','Paid')
                                 ->join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->join('budgets','requisitions.budget_id','budgets.id')
                                 ->where('budgets.is_active', 'Active')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 // ->whereIn('users.stafflevel_id', [$normalStaff])
                                 ->where('users.id', Auth::user()->id)
                                 ->where('requisitions.status', '!=', 'Deleted')
                                 ->where('requisitions.status', '!=', 'Edited')
                                 ->where('departments.status', 'Active')
                                 ->distinct('requisitions.req_no')
                                 ->get();
            return view('requisition.paid-requisitions', compact('paid_requisitions'));
        }elseif(Auth::user()->stafflevel_id == $supervisor){
            $paid_requisitions = Requisition::where('requisitions.status','Paid')
                                 ->join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->join('budgets','requisitions.budget_id','budgets.id')
                                 ->where('budgets.is_active', 'Active')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor])
                                 ->where('users.department_id', $user_dept->dept_id)
                                 ->where('requisitions.status', '!=', 'Deleted')
                                 ->where('requisitions.status', '!=', 'Edited')
                                 ->where('departments.status', 'Active')
                                 ->distinct('requisitions.req_no')
                                 ->get();
            return view('requisition.paid-requisitions', compact('paid_requisitions'));
        }elseif(Auth::user()->stafflevel_id == $hod){
            $paid_requisitions = Requisition::where('requisitions.status','Paid')
                                 ->join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->join('budgets','requisitions.budget_id','budgets.id')
                                 ->where('budgets.is_active', 'Active')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor, $hod])
                                 ->where('users.department_id', $user_dept->dept_id)
                                 ->where('requisitions.status', '!=', 'Deleted')
                                 ->where('requisitions.status', '!=', 'Edited')
                                 ->where('departments.status', 'Active')
                                 ->distinct('requisitions.req_no')
                                 ->get();
            return view('requisition.paid-requisitions', compact('paid_requisitions'));
        }elseif(Auth::user()->stafflevel_id == $ceo || Auth::user()->stafflevel_id == $financeDirector){
            $paid_requisitions = Requisition::where('requisitions.status','Paid')
                                 ->join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->join('budgets','requisitions.budget_id','budgets.id')
                                 ->where('budgets.is_active', 'Active')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 ->whereIn('users.stafflevel_id', [$normalStaff, $supervisor, $hod, $ceo, $financeDirector])
                                 ->where('requisitions.status', '!=', 'Deleted')
                                 ->where('requisitions.status', '!=', 'Edited')
                                 ->where('departments.status', 'Active')
                                 ->distinct('requisitions.req_no')
                                 ->get();
            return view('requisition.paid-requisitions', compact('paid_requisitions'));
        }

    }

    public static function getPaidAmount($req_no)
    {
        $amount_paid = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $req_no)->where('status', 'Pay')->sum('amount_paid');
        $amount_returned = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $req_no)->where('status', 'Return')->sum('amount_paid');
        $paid_amount = $amount_paid + $amount_returned;
        return $paid_amount;
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

    public function deleteRequsitionLine($req_id)
    {
        // $requisition = Requisition::where('id', $req_id)->first();
        // if ($requisition->status == 'Confirmed' || $requisition->status == 'Paid') {
        //     alert()->success('Already approved, cannot delete', 'Opps!');
        //     return redirect()->back();
        // }
        $requisition = RequisitionTemporaryTable::findOrFail($req_id);
        $result = $requisition->where('id', $req_id)->delete();
        // alert()->success('Requisition Line deleted successfuly', 'Good Job');
        return response()->json(['result' => $result]);
    }

    // Deleting the requisition on Requisition Edit Page
    public function deletingRequisition($req_no, $id){
        $requisition = Requisition::where('req_no', $req_no)->where('id', $id)->first();
        if ($requisition->status == 'Confirmed' || $requisition->status == 'Paid') {
            alert()->success('Already approved, cannot delete', 'Opps!');
            return redirect()->back();
        }
        $requisition = EditRequisitionTemporaryTable::findOrFail($id);
        $result = $requisition->where('req_no', $req_no)->where('id', $id)->delete();

        alert()->success('Requisition Line deleted successfuly', 'Good Job');
        return redirect()->back();
    }

    // Deleting the requisition on Requisition View Page
    public function deleteRequisitionById($req_id)
    {
        // $requisition = Requisition::where('id', $req_id)->first();
        // if ($requisition->status == 'Confirmed' || $requisition->status == 'Paid') {
        //     alert()->success('Already approved, cannot delete', 'Opps!');
        //     return redirect()->back();
        // }
        $requisition = EditRequisitionTemporaryTable::findOrFail($req_id);
        $result = $requisition->where('id', $req_id)->delete();
        alert()->success('Requisition Line deleted successfuly', 'Good Job');
        return redirect()->back();
    }

    /*
        Requisition New
    */

    public function renderRequisitionForm()
    {
        $user_id = Auth::user()->id;
        $items = Item::all();
        $budgets = Budget::where('budgets.status', 'Confirmed')->where('is_active', 'Active')->get();
        $accounts = Account::all();
        $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();
        return view('requisitions.create-requisitions')->withBudgets($budgets)->withItems($items)->withData($data)->withAccounts($accounts);
    }



    /*

        New way to handle requisition
    */

    public function createRequisitionForm()
    {

        $status = Department::select('status')->get();
        foreach($status as $status)
        {
            if($status->status == 'Disabled')
            {
                alert()->error('You cannot create a requisition', 'Department Disabled')->persistent('close');
                return redirect(url('/'));
            }else{
            }
        }

        $user_id = Auth::user()->id;
        $items = Item::all();
        $budgets = Budget::where('budgets.status', 'Confirmed')->where('is_active', 'Active')->where('budgets.department_id', Auth::user()->department_id)->get();
        $sub_acc_type = DB::table('sub_account_types')->where('account_subtype_name','Expenditure')->select('id')->value('id');
        $accounts = Account::where('sub_account_type',$sub_acc_type)->get();
        $data = RequisitionTemporaryTable::where('user_id', $user_id)->where('status', 'onprocess')->get();
        $activity = RequisitionTemporaryTable::where('user_id', Auth::user()->id)->select('activity_name')->first();
        return view('requisition.create-requisition', compact('activity'))->withBudgets($budgets)->withItems($items)->withData($data)->withAccounts($accounts);

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
        $budgets = Budget::where('budgets.status', 'Confirmed')->get();
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
        $budgets = Budget::where('budgets.status', 'Confirmed')->get();
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



        $check_requisitions = RequisitionTemporaryTable::all();
        foreach($check_requisitions as $requisition){
            if($requisition->budget_id != $request->budget_id && Auth::user()->id != $request->user_id){
                alert()->error('You cannot mix budgets on the same requisition', 'Oops');
                return redirect()->back();
            }
        }
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

        if ($request->budget_id != 0) {
          
            $budget_category = Budget::join('budget_categories','budgets.budget_category_id','budget_categories.id')->where('budgets.id', $request->budget_id)->select('budgets.budget_category_id')->first();

            if($budget_category->budget_category_id == 1)
            {
                $total = Item::join('budgets','items.budget_id','budgets.id')->where('budget_id', $request->budget_id)
                             ->where('budgets.budget_category_id', 1)->sum('total');

                $total_requested = Requisition::join('budgets','requisitions.budget_id','budgets.id')
                                              ->join('budget_categories','budgets.budget_category_id','budget_categories.id')
                                              ->where('budgets.id', $request->budget_id)
                                              ->where('budget_category_id', 1)
                                              ->where('requisitions.status','!=','Edited')
                                              ->where('requisitions.status','!=','Deleted')
                                              ->sum('gross_amount');

                $total_avalilable = $total - $total_requested;                              

                if($budget_category->budget_category_id == 1)
                {       
                    if($gross_amount > $total_avalilable)
                    {
                        alert()->error('You have exceeded the budget limit')->persistent('close');
                        return redirect()->back();
                    }
                }
            }
        }
        

        

        if (Requisition::select('req_no')->latest()->first() == null && RequisitionTemporaryTable::select('req_no')->latest()->first() == null)
        {
            $req_no = 'Req-1';
        }elseif(Requisition::select('req_no')->latest()->first() != null && RequisitionTemporaryTable::select('req_no')->latest()->first() != null)
        {
            if(Auth::user()->id != $request->user_id)
            {
                $getLatestReqNo = RequisitionTemporaryTable::select('req_no')->latest()->distinct('req_no')->distinct('user_id')->count('req_no');
                $req_no = 'Req-'.($getLatestReqNo + 1);
            }else
            {
                $getLatestReqNo = RequisitionTemporaryTable::select('req_no')->latest()->distinct('req_no')->count('req_no');
                $req_no = 'Req-'.($getLatestReqNo + 1);
            }
        }

        $budget_line = Item::where('budget_id', $request->budget_id)->get();
        $accounts = Account::all();

        session()->put('forms.budget', $request->get('budget_id'));

        DB::table('requisition_temporary_tables')->insert(['req_no' => $request->req_no,'serial_no' => RequisitionsController::getLatestSerialNo() + 1,'budget_id' => $request->budget_id,'item_id' => $request->item_id,'account_id' => $request->account_id, 'user_id' => $request->user_id,'activity_name' => $request->activity_name, 'item_name' => $request->item_name2, 'unit_measure' => $request->unit_measure, 'quantity' => $request->quantity, 'unit_price' => $request->unit_price,
            'vat' => $request->vat, 'description' => $request->description, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount, 'status' => $request->status , 'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);

        if ($request->budget_id != 0) {
          $data = DB::table('requisition_temporary_tables')->join('budgets','requisition_temporary_tables.budget_id','budgets.id')->join('items','requisition_temporary_tables.item_id','items.id')->join('accounts','requisition_temporary_tables.account_id','accounts.id')->select('requisition_temporary_tables.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')->where('req_no', $request->req_no)->where('requisition_temporary_tables.user_id', $request->user_id)->get();

          $view = view('requisition.render-requisition', compact('budget_line','accounts'))->with('data', $data)->render();
        }elseif($request->budget_id == 0){
          $data = DB::table('requisition_temporary_tables')->join('accounts','requisition_temporary_tables.account_id','accounts.id')->select('requisition_temporary_tables.*','accounts.account_name as account')->where('req_no', $request->req_no)->where('requisition_temporary_tables.user_id', $request->user_id)->get();

          $view = view('requisition.render-requisition', compact('budget_line','accounts'))->with('data', $data)->render();
        }

        //return view('requisition.render-requisition')->with('data', $data)->withInput()->render();

        return response()->json(['result' => $view]);
    }

    public function edit_requisition($req_no)
    {
        $data = Requisition::where('req_no', $req_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->where('user_id', Auth::user()->id)->get();

        foreach($data as $data)
        {
            $editedLine = new EditRequisitionTemporaryTable();
            $editedLine->id = $data->id;
            $editedLine->budget_id = $data->budget_id;
            $editedLine->item_id = $data->item_id;
            $editedLine->user_id = $data->user_id;
            $editedLine->account_id = $data->account_id;
            $editedLine->req_no = $data->req_no;
            $editedLine->serial_no = $data->serial_no;
            $editedLine->item_name = $data->item_name;
            $editedLine->description = $data->description;
            $editedLine->unit_measure = $data->unit_measure;
            $editedLine->quantity = $data->quantity;
            $editedLine->unit_price = $data->unit_price;
            $editedLine->vat = $data->vat;

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

            $editedLine->vat_amount = $vat_amount;
            $editedLine->gross_amount = $gross_amount;
            $editedLine->status = 'Edited';
            $editedLine->created_at = $data->created_at;
            $editedLine->updated_at = $data->updated_at;
            $editedLine->activity_name = $data->activity_name;
            $editedLine->post_status = $data->post_status;
            $editedLine->save();
        }

    }

    public function newPermanentRequisitionSubmission(Request $request)
    {
        $check_requisitions = RequisitionTemporaryTable::all();
        foreach($check_requisitions as $requisition){
            if($requisition->budget_id != $request->budget_id){
                alert()->error('You cannot mix budgets on the same requisition', 'Oops');
                return redirect()->back();
            }
        }

        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;



        // if(Auth::user()->stafflevel_id == $normalStaff){

        //     $status = 'onprocess';
        // }elseif(Auth::user()->stafflevel_id == $supervisor){
        //     $status = 'onprocess supervisor';
        // }elseif(Auth::user()->stafflevel_id == $hod){
        //     $status = 'onprocess hod';
        // }elseif(Auth::user()->stafflevel_id == $ceo){
        //     $status = 'onprocess ceo';
        // }

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

        // $status = Requisition::where('req_no', $request->req_no)->select('status')->first();

        if(Auth::user()->stafflevel_id == $normalStaff){

            $status = 'Edited Requisition';
        }elseif(Auth::user()->stafflevel_id == $supervisor){
            $status = 'Edited Requisition';
        }elseif(Auth::user()->stafflevel_id == $hod){
            $status = 'Edited Requisition';
        }elseif(Auth::user()->stafflevel_id == $ceo){
            $status = 'Edited Requisition';
        }

        // if (Requisition::select('req_no')->latest()->first() == null)
        // {
        //     $req_no = 'Req-1';
        // }elseif(Requisition::select('req_no')->latest()->first() != null)
        // {
        //   $getLatestReqNo = Requisition::select('req_no')->latest()->distinct('req_no')->count('req_no');
        //   $req_no = 'Req-'.($getLatestReqNo + 1);
        // }

        $budget_line = Item::where('budget_id', $request->budget_id)->get();
        $accounts = Account::all();

        session()->put('forms.budget', $request->get('budget_id'));
        $serial_no = Requisition::where('req_no', $request->req_no)->select('requisitions.serial_no')->latest()->first();

        DB::table('edit_requisition_temporary_tables')->insert(['req_no' => $request->req_no,'serial_no' => $serial_no->serial_no + 1,'budget_id' => $request->budget_id,'item_id' => $request->item_id,'account_id' => $request->account_id, 'user_id' => $request->user_id,'activity_name' => $request->activity_name, 'item_name' => $request->item_name2, 'unit_measure' => $request->unit_measure, 'quantity' => $request->quantity, 'unit_price' => $request->unit_price,
            'vat' => $request->vat, 'description' => $request->description, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount, 'status' => $status , 'post_status' => 'Not Posted', 'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);

        if ($request->budget_id != 0) {
          $value = DB::table('edit_requisition_temporary_tables')->join('budgets','edit_requisition_temporary_tables.budget_id','budgets.id')->join('items','edit_requisition_temporary_tables.item_id','items.id')->join('accounts','edit_requisition_temporary_tables.account_id','accounts.id')->select('edit_requisition_temporary_tables.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')->where('req_no', $request->req_no)->where('edit_requisition_temporary_tables.status', '!=', 'Deleted')->where('edit_requisition_temporary_tables.status', 'Edited Requisition')->get();

          $view = view('requisition.render-new-requisition', compact('budget_line','accounts'))->with('value', $value)->render();
        }elseif($request->budget_id == 0){
          $value = DB::table('edit_requisition_temporary_tables')->join('accounts','edit_requisition_temporary_tables.account_id','accounts.id')->select('edit_requisition_temporary_tables.*','accounts.account_name as account')->where('req_no', $request->req_no)->where('edit_requisition_temporary_tables.status', '!=', 'Deleted')->where('edit_requisition_temporary_tables.status', 'Edited Requisition')->get();

          $view = view('requisition.render-new-requisition', compact('budget_line','accounts'))->with('value', $value)->render();
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
            // $getLatestRetNo = Retirement::select('req_no')->latest()->distinct('ret_no')->count('ret_no');
            $req_no = 'Req-'.(RequisitionsController::getLatestReqNoCount() + 1);
        }

        return $req_no;
    }

    public function permanentRequisitionSubmission($req_no)
    {

        $staff_levels = StaffLevel::all();

        $hod = $staff_levels[0]->id;
        $ceo = $staff_levels[1]->id;
        $supervisor = $staff_levels[2]->id;
        $normalStaff = $staff_levels[3]->id;
        $financeDirector = $staff_levels[4]->id;



        if(Auth::user()->stafflevel_id == $normalStaff){

            $status = 'onprocess';
        }elseif(Auth::user()->stafflevel_id == $supervisor){
            $status = 'onprocess supervisor';
        }elseif(Auth::user()->stafflevel_id == $hod){
            $status = 'onprocess hod';
        }elseif(Auth::user()->stafflevel_id == $ceo){
            $status = 'onprocess ceo';
        }elseif(Auth::user()->stafflevel_id == $financeDirector){
            $status = 'onprocess finance';
        }

        $requisitions = RequisitionTemporaryTable::where('req_no', $req_no)->where('user_id', Auth::user()->id)->get();

        if (Requisition::select('req_no')->latest()->first() == null)
        {
            $req_no = 'Req-1';
        }elseif(Requisition::select('req_no')->latest()->first() != null)
        {

            $getLatestReqNo = Requisition::select('req_no')->latest()->distinct('req_no')->distinct('user_id')->count('req_no');
            $req_no = 'Req-'.($getLatestReqNo + 1);

        }

        foreach ($requisitions as $requisition) {
            DB::table('requisitions')->insert([
                'budget_id' => $requisition->budget_id,
                'item_id' => $requisition->item_id,
                'account_id' => $requisition->account_id,
                'user_id' => $requisition->user_id,
                'req_no' => $req_no,
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
                'status' => $status,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            RequisitionTemporaryTable::where('user_id', $requisition->user_id)->delete();
        }

        session()->flash('message', 'Retirement has being created');
        return redirect()->route('submitted-requisitions');
    }

    public function bringEditedLineToPermanentTable($user_id, $data_no)
    {
        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        Requisition::where('user_id', $user_id)->where('req_no', $data_no)->update(['status' => 'Edited']);
        $editedLines = EditRequisitionTemporaryTable::where('user_id', $user_id)->where('req_no', $data_no)->where('status', 'Edited')->orWhere('status', 'Edited Requisition')->get();

        foreach ($editedLines as $editedLine) {
            $editedData = new Requisition();
            $editedData->budget_id = $editedLine->budget_id;
            $editedData->item_id = $editedLine->item_id;
            $editedData->user_id = $editedLine->user_id;
            $editedData->account_id = $editedLine->account_id;
            $editedData->req_no = $editedLine->req_no;
            $editedData->serial_no = $editedLine->serial_no;
            $editedData->activity_name = $editedLine->activity_name;
            $editedData->item_name = $editedLine->item_name;
            $editedData->description = $editedLine->description;
            $editedData->unit_measure = $editedLine->unit_measure;
            $editedData->quantity = $editedLine->quantity;
            $editedData->unit_price = $editedLine->unit_price;
            $editedData->vat = $editedLine->vat;
            $editedData->vat_amount = $editedLine->vat_amount;
            $editedData->gross_amount = $editedLine->gross_amount;

            if (Auth::user()->stafflevel_id == $normalStaff) {
                $editedData->status = 'onprocess';
            }elseif(Auth::user()->stafflevel_id == $supervisor){
                $editedData->status = 'onprocess supervisor';
            }elseif(Auth::user()->stafflevel_id == $hod){
                $editedData->status = 'onprocess hod';
            }elseif(Auth::user()->stafflevel_id == $ceo){
                $editedData->status = 'onprocess ceo';
            }elseif(Auth::user()->stafflevel_id == $financeDirector){
                $editedData->status = 'onprocess finance';
            }

            $editedData->post_status = $editedLine->post_status;
            $editedData->created_at = $editedLine->created_at;
            $editedData->updated_at = $editedLine->updated_at;
            $editedData->save();


        }
        // alert()->success('Requisition line updated','');
        EditRequisitionTemporaryTable::truncate();
        return redirect()->back();
    }

    public static function getRequisitionTotal($req_no)
    {
        return Requisition::where('req_no',$req_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->sum('gross_amount');
    }

    public static function amountRetired($ret_no)
    {
        return Retirement::where('ret_no',$ret_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->sum('gross_amount');
    }

    public static function amountUnretired($req_no, $ret_no)
    {
        $result = RequisitionsController::getRequisitionTotal($req_no) - RequisitionsController::amountRetired($ret_no);
        return number_format($result,2);
    }

    public static function amountPaid($req_no)
    {
        $result = FinanceSupportiveDetail::where('req_no', $req_no)->sum('amount_paid');
        return $result;
    }

    public function getAllPaidRequisition($req_no)
    {
        $submitted_requisitions = Requisition::where('req_no', $req_no)
                                  ->where('requisitions.status', 'Paid')
                                  ->join('budgets','requisitions.budget_id','budgets.id')
                                  ->join('items','requisitions.item_id','items.id')
                                  ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                                  ->where('budgets.status', 'Confirmed')
                                  ->get();

        $submitted_paid_no_budget = Requisition::where('requisitions.status','Paid')->where('user_id',Auth::user()->id)->where('req_no', $req_no)->where('budget_id',0)->get();

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

    public function filterByDate(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $paid_requisitions = Requisition::where('requisitions.status','Paid')
                                 ->join('users','requisitions.user_id','users.id')
                                 ->join('departments','users.department_id','departments.id')
                                 ->join('budgets','requisitions.budget_id','budgets.id')
                                 ->where('budgets.is_active', 'Active')
                                 ->select('requisitions.req_no','requisitions.status','users.username as username','departments.name as department')
                                 ->whereDate('requisitions.created_at', '>=', $from)
                                 ->whereDate('requisitions.created_at', '<=', $to)
                                 ->get();

        return view('requisition.paid-requisitions', compact('paid_requisitions'));
    }

    public function filterByDatePending(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

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
                                  WHERE created_at BETWEEN '$from' AND '$to'
                                  LIKE '%Rejected%' AND users.stafflevel_id NOT IN ($supervisor,$hod,$ceo,$financeDirector)"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif(Auth::user()->stafflevel_id == $supervisor)
        {
          $pending_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no
                                  FROM `requisitions`
                                  JOIN users on requisitions.user_id = users.id
                                  JOIN departments on users.department_id = departments.id
                                  WHERE requisitions.status
                                  WHERE created_at BETWEEN '$from' AND '$to'
                                  LIKE '%Rejected%' AND users.stafflevel_id IN ($normalStaff,$supervisor) AND users.stafflevel_id NOT IN ($hod,$financeDirector,$ceo)"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $hod)
        {
          $pending_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no
                                  FROM `requisitions`
                                  JOIN users on requisitions.user_id = users.id
                                  JOIN departments on users.department_id = departments.id
                                  WHERE requisitions.status
                                  WHERE created_at BETWEEN '$from' AND '$to'
                                  LIKE '%Rejected%' AND users.stafflevel_id IN ($normalStaff,$supervisor,$hod,$financeDirector)"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $ceo)
        {
          $pending_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no
                                  FROM `requisitions`
                                  JOIN users on requisitions.user_id = users.id
                                  JOIN departments on users.department_id = departments.id
                                  WHERE requisitions.status
                                  WHERE created_at BETWEEN '$from' AND '$to'
                                  LIKE '%Rejected%' AND users.stafflevel_id IN ($normalStaff,$supervisor,$hod,$financeDirector)"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }elseif (Auth::user()->stafflevel_id == $financeDirector)
        {
            $pending_requisitions = DB::select(DB::raw("SELECT users.username as username, departments.name as department, requisitions.req_no
                                    FROM `requisitions`
                                    JOIN users on requisitions.user_id = users.id
                                    JOIN departments on users.department_id = departments.id
                                    WHERE requisitions.status
                                    WHERE created_at BETWEEN '$from' AND '$to'
                                    LIKE '%Rejected%'"));

            return view('requisitions.view-requisitions', compact('pending_requisitions','staff_levels','requisition'))->withUser($user);
        }
    }

    public function printRequisition($req_no)
    {
        $submitted_requisitions = Requisition::where('requisitions.req_no', $req_no)
                                    ->join('budgets','requisitions.budget_id','budgets.id')
                                    ->join('items','requisitions.item_id','items.id')
                                    ->join('budgets','requisitions.budget_id','budgets.id')
                                    ->where('budgets.is_active', 'Active')
                                    ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                                    ->where('requisitions.status', '!=', 'Deleted')
                                    ->where('requisitions.status', '!=', 'Edited')
                                    ->where('budgets.status', 'Confirmed')
                                    ->distinct('req_no')
                                    ->get();

        $vat_amount = Requisition::where('requisitions.req_no', $req_no)
                                    ->join('budgets','requisitions.budget_id','budgets.id')
                                    ->join('items','requisitions.item_id','items.id')
                                    ->where('requisitions.status', '!=', 'Deleted')
                                    ->where('requisitions.status', '!=', 'Edited')
                                    ->where('budgets.status', 'Confirmed')
                                    ->sum('vat_amount');

        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isRemoteEnabled', TRUE);

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('fontDir/')
        ])->loadView('requisition.requisition-report', compact('submitted_requisitions','req_no','vat_amount'));
        return $pdf->stream('requisition-report');
    }

    public function budgetRestrict($budget_id)
    {
        $counter = null;
        $counter = RequisitionTemporaryTable::count();
        $requisitions = RequisitionTemporaryTable::all();
        if($counter != 0){
            foreach($requisitions as $requisition){
                if($requisition->budget_id != $budget_id){
                    return response()->json(['result' => $requisition->budget_id]);
                }
            }

        }
    }

    public function updateItemName($data_id, $item_name)
    {
        RequisitionTemporaryTable::where('id', $data_id)->update(['item_name' => $item_name]);
        return response()->json(['result' => $item_name]);
    }

    public function updateUnitMeasures($data_id, $unit_measure)
    {
        RequisitionTemporaryTable::where('id', $data_id)->update(['unit_measure' => $unit_measure]);
        return response()->json(['result' => $unit_measure]);
    }

    public function updateQuantity($data_id, $quantity)
    {
        RequisitionTemporaryTable::where('id', $data_id)->update(['quantity' => $quantity]);
        return response()->json(['result' => $quantity]);
    }

    public function updateUnitPrice($data_id, $unit_price)
    {
        RequisitionTemporaryTable::where('id', $data_id)->update(['unit_price' => $unit_price]);
        return response()->json(['result' => $unit_price]);
    }

    public function updateDescription($data_id, $description)
    {
        RequisitionTemporaryTable::where('id', $data_id)->update(['description' => $description]);
        return response()->json(['result' => $description]);
    }

    public function updateVat($data_id, $vat)
    {
        if($vat == 'Non VAT'){
            RequisitionTemporaryTable::where('id', $data_id)->update(['vat' => 'Non VAT']);
        }elseif($vat == 'VAT Exclusive'){
            RequisitionTemporaryTable::where('id', $data_id)->update(['vat' => 'VAT Exclusive']);
        }else{
            RequisitionTemporaryTable::where('id', $data_id)->update(['vat' => 'VAT Inclusive']);
        }
        return response()->json(['result' => $vat]);
    }

    public function updateBudgetLine($data_id, $budget_line)
    {
        RequisitionTemporaryTable::where('id', $data_id)->update(['item_id' => $budget_line]);
        return response()->json(['result' => $budget_line]);
    }

    public function updateAccount($data_id, $account)
    {
        RequisitionTemporaryTable::where('id', $data_id)->update(['account_id' => $account]);
        return response()->json(['result' => $account]);
    }

    // Updating requisitions on permanent (requisitions) table for requisitions with budgets

    public function updateRequisitionItemName($data_id, $item_name)
    {
        EditRequisitionTemporaryTable::where('id', $data_id)->update(['item_name' => $item_name]);
        return response()->json(['result' => $item_name]);
    }

    public function updateRequisitionUnitMeasure($data_id, $unit_measure)
    {
        EditRequisitionTemporaryTable::where('id', $data_id)->update(['unit_measure' => $unit_measure]);
        return response()->json(['result' => $unit_measure]);
    }

    public function updateRequsitionsQuantity($data_id, $quantity)
    {
        $requisition = EditRequisitionTemporaryTable::where('id', $data_id)->first();
        if ($requisition->vat == 'VAT Inclusive')
        {
            $vat_amount = (($quantity * $requisition->unit_price / 1.18) * 0.18);
            $gross_amount = ($quantity * $requisition->unit_price);
        }elseif($requisition->vat == 'VAT Exclusive')
        {
            $vat_amount = (($quantity * $requisition->unit_price * 0.18));
            $gross_amount = ($quantity * $requisition->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($quantity * $requisition->unit_price);
        }

        EditRequisitionTemporaryTable::where('id', $data_id)->update(['quantity' => $quantity, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount]);
        return response()->json(['result' => $quantity, 'gross_amount' => $gross_amount]);
    }

    public function updateRequisitionUnitPrice($data_id, $unit_price)
    {
        $requisition = EditRequisitionTemporaryTable::where('id', $data_id)->first();
        if ($requisition->vat == 'VAT Inclusive')
        {
            $vat_amount = (($requisition->quantity * $unit_price / 1.18) * 0.18);
            $gross_amount = ($requisition->quantity * $unit_price);
        }elseif($requisition->vat == 'VAT Exclusive')
        {
            $vat_amount = (($requisition->quantity * $unit_price * 0.18));
            $gross_amount = ($requisition->quantity * $unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($requisition->quantity * $unit_price);
        }

        EditRequisitionTemporaryTable::where('id', $data_id)->update(['unit_price' => $unit_price, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount]);
        return response()->json(['result' => $unit_price]);
    }

    public function updateRequisitionDescription($data_id, $description)
    {
        EditRequisitionTemporaryTable::where('id', $data_id)->update(['description' => $description]);
        return response()->json(['result' => $description]);
    }

    public function updateRequisitionLine($data_id, $item_id)
    {
        EditRequisitionTemporaryTable::where('id', $data_id)->update(['item_id' => $item_id]);
        return response()->json(['result' => $item_id]);
    }

    public function updateRequsitionVat($data_id, $vat)
    {
        $requisition = EditRequisitionTemporaryTable::where('id', $data_id)->first();
        if ($requisition->vat == 'VAT Inclusive')
        {
            $vat_amount = (($requisition->quantity * $requisition->unit_price / 1.18) * 0.18);
            $gross_amount = ($requisition->quantity * $requisition->unit_price);
        }elseif($requisition->vat == 'VAT Exclusive')
        {
            $vat_amount = (($requisition->quantity * $requisition->unit_price * 0.18));
            $gross_amount = ($requisition->quantity * $requisition->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($requisition->quantity * $requisition->unit_price);
        }

        EditRequisitionTemporaryTable::where('id', $data_id)->update(['vat' => $vat, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount]);
        return response()->json(['result' => $vat]);
    }

    public function updateRequisitionAccount($data_id, $account)
    {
        EditRequisitionTemporaryTable::where('id', $data_id)->update(['account_id' => $account]);
        return response()->json(['result' => $account]);
    }

    // Updating requisitions on permanent (requisitions) table for requisitions with no budgets

    public function updateNoBudgetRequisitionItemName($data_id, $item_name)
    {
        EditRequisitionTemporaryTable::where('id', $data_id)->update(['item_name' => $item_name]);
        return response()->json(['result' => $item_name]);
    }

    public function updateNoBudgetRequisitionUnitMeasure($data_id, $unit_measure)
    {
        EditRequisitionTemporaryTable::where('id', $data_id)->update(['unit_measure' => $unit_measure]);
        return response()->json(['result' => $unit_measure]);
    }

    public function updateNoBudgetRequisitionQuantity($data_id, $quantity)
    {
        $requisition = EditRequisitionTemporaryTable::where('id', $data_id)->first();
        if ($requisition->vat == 'VAT Inclusive')
        {
            $vat_amount = (($quantity * $requisition->unit_price / 1.18) * 0.18);
            $gross_amount = ($quantity * $requisition->unit_price);
        }elseif($requisition->vat == 'VAT Exclusive')
        {
            $vat_amount = (($quantity * $requisition->unit_price * 0.18));
            $gross_amount = ($quantity * $requisition->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($quantity * $requisition->unit_price);
        }

        EditRequisitionTemporaryTable::where('id', $data_id)->update(['quantity' => $quantity, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount]);
        return response()->json(['result' => $quantity]);
    }

    public function updateNoBudgetRequisitionUnitPrice($data_id, $unit_price)
    {
        $requisition = EditRequisitionTemporaryTable::where('id', $data_id)->first();
        if ($requisition->vat == 'VAT Inclusive')
        {
            $vat_amount = (($requisition->quantity * $unit_price / 1.18) * 0.18);
            $gross_amount = ($requisition->quantity * $unit_price);
        }elseif($requisition->vat == 'VAT Exclusive')
        {
            $vat_amount = (($requisition->quantity * $unit_price * 0.18));
            $gross_amount = ($requisition->quantity * $unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($requisition->quantity * $unit_price);
        }

        EditRequisitionTemporaryTable::where('id', $data_id)->update(['unit_price' => $unit_price, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount]);
        return response()->json(['result' => $unit_price]);
    }

    public function updateNoBudgetRequisitionDescription($data_id, $description)
    {
        EditRequisitionTemporaryTable::where('id', $data_id)->update(['description' => $description ]);
        return response()->json(['result' => $description]);
    }

    public function updateNoBudgetRequisitionVat($data_id, $vat)
    {
        $requisition = EditRequisitionTemporaryTable::where('id', $data_id)->first();
        if ($requisition->vat == 'VAT Inclusive')
        {
            $vat_amount = (($requisition->quantity * $requisition->unit_price / 1.18) * 0.18);
            $gross_amount = ($requisition->quantity * $requisition->unit_price);
        }elseif($requisition->vat == 'VAT Exclusive')
        {
            $vat_amount = (($requisition->quantity * $requisition->unit_price * 0.18));
            $gross_amount = ($requisition->quantity * $requisition->unit_price * 1.18);
        }else
        {
            $vat_amount = 0;
            $gross_amount = ($requisition->quantity * $requisition->unit_price);
        }

        EditRequisitionTemporaryTable::where('id', $data_id)->update(['vat' => $vat, 'vat_amount' => $vat_amount, 'gross_amount' => $gross_amount]);
        return response()->json(['result' => $vat]);
    }

    public function updateNoBudgetRequisitionAccount($data_id, $account)
    {
        EditRequisitionTemporaryTable::where('id', $data_id)->update(['account_id' => $account]);
        return response()->json(['result' =>$account]);
    }

    public function truncateRequisitionOnRefresh($user_id)
    {
        foreach(RequisitionTemporaryTable::where('user_id', $user_id)->get() as $requisition)
        {
            $result = $requisition->delete();
        }
        return  response()->json(['result' => $result]);
    }

    public function truncateEditedLinesOnReset($user_id)
    {
        foreach(EditRequisitionTemporaryTable::where('user_id', $user_id)->get() as $requisition)
        {
            $result = $requisition->delete();
        }
        return  response()->json(['result' => $result]);
    }

    public function getTotalOnEdit($req_no)
    {
        $gross_amount = Requisition::where('req_no',$req_no)->where('status', '!=', 'Deleted')->sum('gross_amount');
        return response()->json(['gross_amount' => $gross_amount]);
    }

    public function getActivityName()
    {
        $result = RequisitionTemporaryTable::where('user_id', Auth::user()->id)->select('activity_name')->first();
        if ($result == null || $result != null) {
            return response()->json(['result' => $result]);
        }
    }

    public function confirmationForm($req_no)
    {
        $paid_to = Requisition::join('users','requisitions.user_id','users.id')
                   ->where('req_no', $req_no)
                   ->select('users.username')->first();
        $payer_name = User::where('users.id', Auth::user()->id)->select('users.username')->first();
        $payment_details = FinanceSupportiveDetail::join('accounts','finance_supportive_details.account_id','accounts.id')
                            ->select('finance_supportive_details.*','accounts.account_name as account')
                            ->where('accounts.sub_account_type',3)
                            ->where('req_no', $req_no)
                            ->latest()
                            ->first();

        return view('requisitions.confirmation-form', compact('payment_details','req_no','paid_to','payer_name'));
    }

    public static function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'Zero',
            1                   => 'One',
            2                   => 'Two',
            3                   => 'Three',
            4                   => 'Four',
            5                   => 'Five',
            6                   => 'Six',
            7                   => 'Seven',
            8                   => 'Eight',
            9                   => 'Nine',
            10                  => 'Ten',
            11                  => 'Eleven',
            12                  => 'Twelve',
            13                  => 'Thirteen',
            14                  => 'Fourteen',
            15                  => 'Fifteen',
            16                  => 'Sixteen',
            17                  => 'Seventeen',
            18                  => 'Eighteen',
            19                  => 'Nineteen',
            20                  => 'Twenty',
            30                  => 'Thirty',
            40                  => 'Fourty',
            50                  => 'Fifty',
            60                  => 'Sixty',
            70                  => 'Seventy',
            80                  => 'Eighty',
            90                  => 'Ninety',
            100                 => 'Hundred',
            1000                => 'Thousand',
            1000000             => 'Million',
            1000000000          => 'Billion',
            1000000000000       => 'Trillion',
            1000000000000000    => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    public static function generateVoucherNo()
    {
        $string = "VC-".str_random(3);
        return strtoupper($string);
    }

    public function printConfirmationForm($req_no)
    {
        $paid_to = Requisition::join('users','requisitions.user_id','users.id')
                   ->where('req_no', $req_no)
                   ->select('users.username')->first();
        $payer_name = User::where('users.id', Auth::user()->id)->select('users.username')->first();
        $payment_details = FinanceSupportiveDetail::join('accounts','finance_supportive_details.account_id','accounts.id')
                            ->select('finance_supportive_details.*','accounts.account_name as account')
                            ->where('accounts.sub_account_type',3)
                            ->where('req_no', 'Req-7')
                            ->latest()
                            ->first();

        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isRemoteEnabled', TRUE);

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('fontDir/')
        ])->loadView('requisitions.confirmation-form-pdf', compact('payment_details','req_no','paid_to','payer_name'));
        return $pdf->stream('Confirmation Form');
    }

}
