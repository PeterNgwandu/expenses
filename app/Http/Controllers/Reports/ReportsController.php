<?php

namespace App\Http\Controllers\Reports;

use PDF;

use App\Item\Item;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Budget\Budget;
use App\BudgetCategory;
use Illuminate\Http\Request;
use App\Imports\BudgetImport;
use App\Department\Department;
use App\StaffLevel\StaffLevel;
use App\Imports\ImprestImport;
use Illuminate\Support\Carbon;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Imports\RefundsReceivedImport;
use App\Accounts\FinanceSupportiveDetail;

class ReportsController extends Controller
{
	/*................................*/

		/* Budget Report Methods

	/*................................*/

	public function showBudgets()
	{
    $staff_levels = StaffLevel::all();
    $hod = $staff_levels[0]->id;
    $ceo = $staff_levels[1]->id;
    $supervisor = $staff_levels[2]->id;
    $normalStaff = $staff_levels[3]->id;
    $financeDirector = $staff_levels[4]->id;

    if (Auth::user()->stafflevel_id == $financeDirector || Auth::user()->stafflevel_id == $ceo) {
        $budgets = Budget::join('budget_categories','budgets.budget_category_id','budget_categories.id')
                   ->select('budgets.*','budget_categories.name as category')
                   ->where('status', 'Confirmed')
                   ->get();
        return view('reports.budgets.view-budgets', compact('budgets'));
    }elseif(Auth::user()->stafflevel_id == $hod)
    {
        $budgets = Budget::join('budget_categories','budgets.budget_category_id','budget_categories.id')
                   ->select('budgets.*','budget_categories.name as category')
                   ->where('status', 'Confirmed')
                   ->where('budgets.department_id', Auth::user()->department_id)
                   ->get();
        return view('reports.budgets.view-budgets', compact('budgets'));
    }

	}

	public function generateBudgetReport($budget_id)
	{
		$budget = Budget::findOrFail($budget_id);
		$budgets = Budget::join('items','budgets.id','items.budget_id')
                   ->select('items.*','items.id as item_id')
                   ->where('budgets.status', 'Confirmed')
                   ->where('items.budget_id', $budget_id)
                   ->get();
		return view('reports.budgets.budget-report', compact('budget','budgets','budget_id'));
	}

	public static function calculateCommittedAmount($budget_id, $item_id)
	{
		    $amountCommitted = Budget::join('requisitions','budgets.id','requisitions.budget_id')
                                 ->join('items','requisitions.item_id','items.id')
		                             ->where('requisitions.budget_id',$budget_id)
		                             ->where('requisitions.item_id',$item_id)
                                 // ->where('requisitions.status', '!=', 'Paid')
                                 ->where('requisitions.status', '!=', 'onprocess')
                                 ->where('requisitions.status', '!=', 'onprocess supervisor')
                                 ->where('requisitions.status', '!=', 'onprocess hod')
                                 ->where('requisitions.status', '!=', 'onprocess finance')
                                 ->where('requisitions.status', '!=', 'onprocess ceo')
		                         ->sum('requisitions.gross_amount');

        $amount_paid = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                       ->where('requisitions.budget_id', $budget_id)
                       ->where('requisitions.item_id', $item_id)
                       ->sum('finance_supportive_details.amount_paid');

    // return $amountCommitted - $amount_paid;                         
		return $amountCommitted - (ReportsController::calculateAmountSpent($budget_id, $item_id) + ReportsController::calculateVATByItem($budget_id, $item_id));                         
	}

    public static function calculateUncommitedAmount($budget_id, $item_id)
    {
        $amountUncommitted = Requisition::where('requisitions.budget_id',$budget_id)
                             ->where('requisitions.item_id',$item_id)
                             ->where('requisitions.status','!=','Confirmed')
                             ->where('requisitions.status', '!=', 'Paid')
                             ->where('requisitions.status', '!=', 'Approved By Supervisor')
                             ->where('requisitions.status', '!=', 'Approved By HOD')
                             ->where('requisitions.status', '!=', 'Approved By Finance')
                             // ->orWhere('requisitions.status','onprocess ceo')
                             // ->orWhere('requisitions.status','onprocess finance')
                             // ->orWhere('requisitions.status','onprocess hod')
                             // ->orWhere('requisitions.status','onprocess supervisor')
                             ->sum('requisitions.gross_amount');
                             
        return $amountUncommitted;
    }

    public static function calculateGrossSpent($budget_id, $item_id)
    {
        $gross_spent = ReportsController::calculateUncommitedAmount($budget_id,$item_id) + 
                       ReportsController::calculateCommittedAmount($budget_id, $item_id) + 
                       ReportsController::calculateAmountSpent($budget_id, $item_id);

        return $gross_spent + ReportsController::calculateVATByItem($budget_id, $item_id);
    }

	public static function calculateAmountNotRetired($budget_id, $item_id)
	{
		$amountSpent = Budget::join('requisitions','budgets.id','requisitions.budget_id')
		                         ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
		                         ->join('items','requisitions.item_id','items.id')
		                         ->where('requisitions.budget_id',$budget_id)
		                         ->where('requisitions.item_id',$item_id)
		                         ->where('requisitions.status', 'Paid')
		                         ->where('finance_supportive_details.status', 'Pay')
		                         ->sum('amount_paid');
		return $amountSpent;
	}

    public static function calculateAmountSpent($budget_id, $item_id)
    {
        $amountSpent = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                       ->where('requisitions.budget_id', $budget_id)
                       ->where('requisitions.item_id', $item_id)
                       ->where('retirements.status', '!=', 'Retired')
                       ->where('retirements.status', '!=', 'Reitired, supervisor')
                       ->where('retirements.status', '!=', 'Retired, hod')
                       ->where('retirements.status', '!=', 'Retired, finance')
                       ->where('retirements.status', '!=', 'Retired, ceo')
                       ->distinct()
                       ->sum('retirements.gross_amount');

        return $amountSpent - ReportsController::calculateVATByItem($budget_id, $item_id);
    }

    public static function calculateVATByItem($budget_id, $item_id)
    {
        $totalVATByItem = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                       ->where('requisitions.budget_id', $budget_id)
                       ->where('requisitions.item_id', $item_id)
                       ->where('retirements.status', '!=', 'Retired')
                       ->where('retirements.status', '!=', 'Reitired, supervisor')
                       ->where('retirements.status', '!=', 'Retired, hod')
                       ->where('retirements.status', '!=', 'Retired, finance')
                       ->where('retirements.status', '!=', 'Retired, ceo')
                       ->distinct()
                       ->sum('retirements.vat_amount');

        return $totalVATByItem;               
    }

    public static function calculateNotSpentAmount($budget_id, $item_id)
    {
        $amountNotSpent = Budget::join('requisitions','budgets.id','requisitions.budget_id')
                                 ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                                 ->join('items','requisitions.item_id','items.id')
                                 ->join('retirements','requisitions.req_no','retirements.req_no')
                                 ->where('retirements.req_no', '')
                                 ->where('requisitions.budget_id',$budget_id)
                                 ->where('requisitions.item_id',$item_id)
                                 ->where('requisitions.status', 'Paid')
                                 ->where('finance_supportive_details.status', 'Pay')
                                 ->sum('amount_paid');

        $amountSpent = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                       ->where('requisitions.budget_id', $budget_id)
                       ->where('requisitions.item_id', $item_id)
                       ->where('retirements.status', '!=', 'Retired')
                       ->where('retirements.status', '!=', 'Reitired, supervisor')
                       ->where('retirements.status', '!=', 'Retired, hod')
                       ->where('retirements.status', '!=', 'Retired, finance')
                       ->where('retirements.status', '!=', 'Retired, ceo')
                       ->sum('retirements.gross_amount');                         

        return ReportsController::calculateAmountNotRetired($budget_id, $item_id) - $amountNotSpent - $amountSpent;
    }


	public static function totalBudgetById($budget_id)
    {
        return Item::where('budget_id', $budget_id)->sum('total');
    }

    public static function amountAvailable($budget_id)
    {                                                
		$totalAmount = ReportsController::totalBudgetById($budget_id);                         

    	$amountAvailable = $totalAmount - ReportsController::totalGrossSpent($budget_id);
    	return $amountAvailable;
    }

    public static function totalUncommitted($budget_id)
    {
        $amountUncommitted = Budget::join('requisitions','budgets.id','requisitions.budget_id')
                                 ->join('items','requisitions.item_id','items.id')
                                 ->where('requisitions.budget_id',$budget_id)
                                 ->where('requisitions.status','!=','Confirmed')
                                 ->where('requisitions.status', '!=', 'Paid')
                                 ->where('requisitions.status', '!=', 'Approved By Supervisor')
                                 ->where('requisitions.status', '!=', 'Approved By HOD')
                                 ->where('requisitions.status', '!=', 'Approved By Finance')
                                 ->sum('gross_amount');
        return $amountUncommitted;
    }

    public static function totalCommitted($budget_id)
    {
        $amountCommitted = Budget::join('requisitions','budgets.id','requisitions.budget_id')
                                 ->join('items','requisitions.item_id','items.id')
                                 ->where('requisitions.budget_id',$budget_id)
                                 // ->where('requisitions.status', '!=', 'Paid')
                                 ->where('requisitions.status', '!=', 'onprocess')
                                 ->where('requisitions.status', '!=', 'onprocess supervisor')
                                 ->where('requisitions.status', '!=', 'onprocess hod')
                                 ->where('requisitions.status', '!=', 'onprocess finance')
                                 ->where('requisitions.status', '!=', 'onprocess ceo')
                                 ->distinct()
                                 ->sum('gross_amount');

        $amount_paid = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                       ->where('requisitions.budget_id', $budget_id)
                       ->sum('finance_supportive_details.amount_paid');

        // $result = $amountCommitted - $amount_paid;
        $result = $amountCommitted - (ReportsController::totalSpent($budget_id) + ReportsController::calculateVAT($budget_id));
        
        return $result;               
    }

    public static function totalSpent($budget_id)
    {
        $amountSpent = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                       ->where('requisitions.budget_id', $budget_id)
                       ->where('retirements.status', '!=', 'Retired')
                       ->where('retirements.status', '!=', 'Reitired, supervisor')
                       ->where('retirements.status', '!=', 'Retired, hod')
                       ->where('retirements.status', '!=', 'Retired, finance')
                       ->where('retirements.status', '!=', 'Retired, ceo')
                       ->distinct()
                       ->sum('retirements.gross_amount');

        return $amountSpent - ReportsController::calculateVAT($budget_id);
    }

    public static function calculateVAT($budget_id)
    {
        $vatTotal = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                       ->where('requisitions.budget_id', $budget_id)
                       ->where('retirements.status', '!=', 'Retired')
                       ->where('retirements.status', '!=', 'Reitired, supervisor')
                       ->where('retirements.status', '!=', 'Retired, hod')
                       ->where('retirements.status', '!=', 'Retired, finance')
                       ->where('retirements.status', '!=', 'Retired, ceo')
                       ->distinct()
                       ->sum('retirements.vat_amount');

        return $vatTotal;               
    }

    public static function totalNotSpent($budget_id)
    {
        $amountNotSpent = Budget::join('requisitions','budgets.id','requisitions.budget_id')
                                 ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                                 ->join('items','requisitions.item_id','items.id')
                                 ->join('retirements','requisitions.req_no','retirements.req_no')
                                 ->where('retirements.req_no', '')
                                 ->where('requisitions.budget_id',$budget_id)
                                 ->where('requisitions.status', 'Paid')
                                 ->where('finance_supportive_details.status', 'Pay')
                                 ->sum('amount_paid');

        $amountSpent = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')
                       ->where('requisitions.budget_id', $budget_id)
                       ->where('retirements.status', '!=', 'Retired')
                       ->where('retirements.status', '!=', 'Reitired, supervisor')
                       ->where('retirements.status', '!=', 'Retired, hod')
                       ->where('retirements.status', '!=', 'Retired, finance')
                       ->where('retirements.status', '!=', 'Retired, ceo')
                       ->sum('retirements.gross_amount');                         

        $amount_not_retired = ReportsController::calculateAmountNotRetiredByBudget($budget_id);
        
        return $amount_not_retired - $amountNotSpent - $amountSpent;                         
    }

    public static function calculateAmountNotRetiredByBudget($budget_id)
    {
        $amountSpent = Budget::join('requisitions','budgets.id','requisitions.budget_id')
                                 ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                                 ->join('items','requisitions.item_id','items.id')
                                 ->where('requisitions.budget_id',$budget_id)
                                 ->where('requisitions.status', 'Paid')
                                 ->where('finance_supportive_details.status', 'Pay')
                                 ->sum('amount_paid');
        return $amountSpent;
    }

    public static function totalGrossSpent($budget_id)
    {
        $gross_spent = ReportsController::totalUncommitted($budget_id) + 
                       ReportsController::totalCommitted($budget_id) + 
                       ReportsController::totalSpent($budget_id);

        return $gross_spent;
    }

    public function printBudgetReport($budget_id)
    {	
    	$budget = Budget::findOrFail($budget_id);
		$budgets = Budget::join('items','budgets.id','items.budget_id')
                   ->select('items.*')
                   ->where('budgets.status', 'Confirmed')
                   ->where('items.budget_id', $budget_id)
                   ->get();

    	$pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('fontDir/')
        ])->loadView('reports.budgets.budget-report-pdf', compact('budget','budgets','budget_id'))
          ->setPaper('a4','landscape');
        return $pdf->stream('budgets-report-pdf');
    }

    public function exportBudgets($budget_id)
    {
        return Excel::download(new BudgetImport, 'budgets-report.xlsx');
    }

    /*...................................*/

    	// End Of Budgets Report Methods

    /*...................................*/


    /*................................................................................................................*/


    /*...................................*/

    	// Uretired Imprest Report Methods

    /*...................................*/

    public function showUnretiredImprests()
    {
    	$req_no = Retirement::select('req_no')->distinct()->get()->pluck('req_no');

    	$unretired_imprest = Requisition::join('users','requisitions.user_id','users.id')
    	                     ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
    	                     ->join('departments','users.department_id','departments.id')
    	                     ->select('requisitions.*','users.username as requester','finance_supportive_details.payment_date','departments.name as department')
    	                     ->where('requisitions.status', 'Paid')
    	                     ->whereNotIn('requisitions.req_no', $req_no->toArray())
    	                     ->groupBy('req_no')
	                         ->get();

	    $unretired_imprest_dates = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
    	                     ->select('finance_supportive_details.payment_date')
    	                     ->where('requisitions.status', 'Paid')
    	                     ->whereNotIn('requisitions.req_no', $req_no->toArray())
	                         ->get()
	                         ->pluck('payment_date');                     

	    $myArray = $unretired_imprest_dates->toArray();
	    $from = reset($myArray);
	    $to = end($myArray);                     

    	return view('reports.imprests.view-unretired-imprests', compact('unretired_imprest','from','to'));            
    }

    public function exportImprests($from, $to)
    {
        return Excel::download(new ImprestImport, 'unretired-imprest-report.xlsx');
    }

    public static function calculateAmountPaid($req_no)
    {
    	$amount_paid = FinanceSupportiveDetail::where('req_no',$req_no)->sum('amount_paid');
    	return $amount_paid;
    }

    public static function calculateAmountRequested($req_no)
    {
    	$amount_requested = Requisition::where('req_no', $req_no)
    						->where('requisitions.status', '!=', 'Edited')
    						->where('requisitions.status', '!=', 'Deleted')
    	                    ->sum('gross_amount');

    	return $amount_requested;
    }

    public static function calculateTotalUnretiredImrestsCosts()
    {
    	$req_no = Retirement::select('req_no')->distinct()->get()->pluck('req_no');

    	$req_No = Requisition::select('req_no')
    	                     ->where('requisitions.status', 'Paid')
    	                     ->whereNotIn('requisitions.req_no', $req_no->toArray())
    	                     ->distinct()
	                         ->get()
	                         ->pluck('req_no');

    	$unretired_imprest_total = FinanceSupportiveDetail::whereIn('finance_supportive_details.req_no', $req_No->toArray())
    	                                   ->sum('amount_paid');

	    return $unretired_imprest_total;                    
    }

    public function printUnretiredImprestReport($from, $to)
    {
    	$req_no = Retirement::select('req_no')->distinct()->get()->pluck('req_no');

    	$unretired_imprest = Requisition::join('users','requisitions.user_id','users.id')
    	                     ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
    	                     ->join('departments','users.department_id','departments.id')
    	                     ->select('requisitions.*','users.username as requester','finance_supportive_details.payment_date','departments.name as department')
    	                     ->where('requisitions.status', 'Paid')
    	                     ->whereNotIn('requisitions.req_no', $req_no->toArray())
    	                     ->whereDate('finance_supportive_details.payment_date', '>=', $from)
    	                     ->whereDate('finance_supportive_details.payment_date', '<=', $to)
    	                     ->groupBy('req_no')
	                         ->get();

	    $options = new Options();
        $options->set('defaultFont', 'Times Roman');
        $options->set('isRemoteEnabled', TRUE);

        $pdf = new Dompdf();
        $pdf->set_paper(array(0,0,420,595), 'landscape');                     

	    $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('fontDir/')
        ])->loadView('reports.imprests.unretired-imprests-report-pdf', compact('req_no','unretired_imprest','from','to'))->setPaper('a4', 'landscape');
        return $pdf->stream('unretired-imprests-report-pdf');                     
    }

    public function reportCustomFilter(Request $request)
    {
    	$from = Carbon::parse($request->from);
    	$to = Carbon::parse($request->to);

    	$req_no = Retirement::select('req_no')->distinct()->get()->pluck('req_no');

    	$unretired_imprest = Requisition::join('users','requisitions.user_id','users.id')
    	                     ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
    	                     ->join('departments','users.department_id','departments.id')
    	                     ->select('requisitions.*','users.username as requester','finance_supportive_details.payment_date','departments.name as department')
    	                     ->where('requisitions.status', 'Paid')
    	                     ->whereNotIn('requisitions.req_no', $req_no->toArray())
    	                     ->whereDate('finance_supportive_details.payment_date', '>=', $from)
    	                     ->whereDate('finance_supportive_details.payment_date', '<=', $to)
    	                     ->groupBy('req_no')
	                         ->get();

	    ReportsController::calculateTotalUnretiredImrestsCostsBasedOnFilter($from, $to);                

	    return view('reports.imprests.view-unretired-imprests', compact('unretired_imprest', 'from', 'to'));                     
    }

    public static function calculateTotalUnretiredImrestsCostsBasedOnFilter($from, $to)
    {
    	$from = Carbon::parse($from);
    	$to = Carbon::parse($to);

    	$req_no = Retirement::select('req_no')->distinct()->get()->pluck('req_no');

    	$req_No = Requisition::select('req_no')
    	                     ->where('requisitions.status', 'Paid')
    	                     ->whereNotIn('requisitions.req_no', $req_no->toArray())
    	                     ->distinct()
	                         ->get()
	                         ->pluck('req_no');

    	$unretired_imprest_total = FinanceSupportiveDetail::whereIn('finance_supportive_details.req_no', $req_No->toArray())
                                   ->whereDate('finance_supportive_details.payment_date', '>=', $from)
                                   ->whereDate('finance_supportive_details.payment_date', '<=', $to)
                                   ->sum('amount_paid');

        return $unretired_imprest_total;                           
    }

    public function reportAgingFilter($option)
    {
    	$req_no = Retirement::select('req_no')->distinct()->get()->pluck('req_no');

    	$unretired_imprest = Requisition::join('users','requisitions.user_id','users.id')
    	                     ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
    	                     ->join('departments','users.department_id','departments.id')
    	                     ->select('requisitions.*','users.username as requester','finance_supportive_details.payment_date','departments.name as department')
    	                     ->where('requisitions.status', 'Paid')
    	                     ->whereNotIn('requisitions.req_no', $req_no->toArray())
    	                     ->groupBy('req_no')
	                         ->get();

	    $unretired_imprest_dates = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
    	                     ->select('finance_supportive_details.payment_date')
    	                     ->where('requisitions.status', 'Paid')
    	                     ->whereNotIn('requisitions.req_no', $req_no->toArray())
	                         ->get()
	                         ->pluck('payment_date');                     

	    $myArray = $unretired_imprest_dates->toArray();
	    $from = reset($myArray);
	    $to = end($myArray);   

	    $days = Carbon::parse(Carbon::now())->diffAsCarbonInterval(Carbon::parse($from))->totalDays;    

	    if($option >= 365)
	    {
    	    return response()->json(['result' => $option]);
	    }elseif ($option == 180 && $option <= 265) {
    	    return response()->json(['result' => $option]);

	    }elseif ($option == 60 && $option <= 180) {
	    	return response()->json(['result' => $option]);
	    }elseif($option == 30 && $option <= 60){
	    	return response()->json(['result' => $option]);
	    }

    }

    /*...................................*/

        // Refunds Received

    /*...................................*/

    public function showRefundsReceived()
    {

        $balance_received = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no','requisitions.activity_name','finance_supportive_details.amount_paid','users.username as username','departments.name as department','requisitions.created_at','finance_supportive_details.payment_date')
                          ->where('finance_supportive_details.status','Receive')

                          ->distinct()
                          ->groupBy('finance_supportive_details.created_at')
                          ->get();

        $balance_received_req_no = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no')
                          ->where('finance_supportive_details.status','Receive')
                          ->distinct()
                          ->groupBy('requisitions.req_no')
                          ->get(); 

        $balance_received_req_nos = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no')
                          ->where('finance_supportive_details.status','Receive')
                          ->distinct()
                          ->groupBy('requisitions.req_no')
                          ->pluck('requisitions.req_no');                 

        $balance_received_dates = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no','finance_supportive_details.payment_date')
                          ->where('finance_supportive_details.status','Receive')
                          ->whereIn('finance_supportive_details.req_no', $balance_received_req_nos->toArray())
                          ->distinct()
                          // ->groupBy('requisitions.req_no')
                          ->pluck('finance_supportive_details.payment_date');                                  

        $myArray =  $balance_received_dates->toArray();                 
        $from = reset($myArray);
        $to = end($myArray);

        return view('reports.refunds_received.refunds_received', compact('balance_received','balance_received_req_no','from','to'));                  
    }

    public function groupingReceivedFunds(Request $request)
    {

        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $balance_received = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no','requisitions.activity_name','finance_supportive_details.amount_paid','users.username as username','departments.name as department','requisitions.created_at','finance_supportive_details.payment_date')
                          ->where('finance_supportive_details.status','Receive')
                          ->where('requisitions.req_no', $request->req_no)
                          ->distinct()
                          ->groupBy('finance_supportive_details.created_at')
                          ->get();

        $balance_received_req_no = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no')
                          ->where('finance_supportive_details.status','Receive')
                          ->distinct()
                          ->groupBy('requisitions.req_no')
                          ->get();                  

        return view('reports.refunds_received.refunds_received', compact('balance_received','balance_received_req_no','from','to'));                  
    }

    public function refundReceivedCustomFilter(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $balance_received = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no','requisitions.activity_name','finance_supportive_details.amount_paid','users.username as username','departments.name as department','requisitions.created_at','finance_supportive_details.payment_date')
                          ->where('finance_supportive_details.status','Receive')
                          // ->where('requisitions.req_no', $request->req_no)
                          ->whereDate('finance_supportive_details.payment_date', '>=', $from)
                          ->whereDate('finance_supportive_details.payment_date', '<=', $to)
                          ->distinct()
                          ->groupBy('finance_supportive_details.created_at')
                          ->get();

        $balance_received_req_no = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no')
                          ->where('finance_supportive_details.status','Receive')
                          ->distinct()
                          ->groupBy('requisitions.req_no')
                          ->get();                  

        // ReportsController::calculateTotalUnretiredImrestsCostsBasedOnFilter($from, $to);                

        return view('reports.refunds_received.refunds_received', compact('balance_received','balance_received_req_no','to','from'));                     
    }

    public static function refundsReceivedTotalBasedOnPaymentDate($from, $to)
    {
        $total_funds_received = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                              ->join('users','requisitions.user_id','users.id')
                              ->join('departments','users.department_id','departments.id')
                              ->select('requisitions.req_no','requisitions.activity_name','finance_supportive_details.amount_paid','users.username as username','departments.name as department','requisitions.created_at','finance_supportive_details.payment_date')
                              ->where('finance_supportive_details.status','Receive')
                              ->whereDate('finance_supportive_details.payment_date', '>=', $from)
                              ->whereDate('finance_supportive_details.payment_date', '<=', $to)
                              ->distinct()
                              ->sum('finance_supportive_details.amount_paid');

        return $total_funds_received;                      
    }

    public static function refundsReceivedTotalBasedOnReqNo(Request $request)
    {
        $total_funds_received = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                              ->join('users','requisitions.user_id','users.id')
                              ->join('departments','users.department_id','departments.id')
                              ->select('requisitions.req_no','requisitions.activity_name','finance_supportive_details.amount_paid','users.username as username','departments.name as department','requisitions.created_at','finance_supportive_details.payment_date')
                              ->where('finance_supportive_details.status','Receive')
                              ->where('finance_supportive_details.req_no', $request->req_no)
                              ->distinct()
                              ->sum('finance_supportive_details.amount_paid');

        return $total_funds_received;                      
    }

    public function exportRefundsReceived($from, $to)
    {
        return Excel::download(new RefundsReceivedImport, 'refunds_received_report.xlsx');
    }

    public function printRefundsReceived($from, $to)
    {

        $balance_received = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no','requisitions.activity_name','finance_supportive_details.amount_paid','users.username as username','departments.name as department','requisitions.created_at','finance_supportive_details.payment_date')
                          ->where('finance_supportive_details.status','Receive')
                          // ->where('requisitions.req_no', $request->req_no)
                          ->whereDate('finance_supportive_details.payment_date', '>=', $from)
                          ->whereDate('finance_supportive_details.payment_date', '<=', $to)
                          ->distinct()
                          ->groupBy('finance_supportive_details.created_at')
                          ->get();

        $balance_received_req_no = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no')
                          ->where('finance_supportive_details.status','Receive')
                          ->distinct()
                          ->groupBy('requisitions.req_no')
                          ->get();

        $options = new Options();
        $options->set('defaultFont', 'Times Roman');
        $options->set('isRemoteEnabled', TRUE);

        $pdf = new Dompdf();
        $pdf->set_paper(array(0,0,420,595), 'landscape');                     

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('fontDir/')
        ])->loadView('reports.refunds_received.pdf.refunds_received_pdf', compact('balance_received','balance_received_req_no','from','to'))->setPaper('a4', 'landscape');
        return $pdf->stream('unretired-imprests-report-pdf');
    }

}
