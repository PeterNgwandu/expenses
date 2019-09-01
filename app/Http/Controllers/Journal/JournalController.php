<?php

namespace App\Http\Controllers\Journal;

use DB;
use PDF;
use App\Journal\Journal;
use App\Accounts\Account;
use Illuminate\Http\Request;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use App\Journal\RetirementsJournal;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Imports\ImprestJournalImport;
use App\Imports\RetirementJournalImport;
use App\Accounts\FinanceSupportiveDetail;
use App\ExpenseRetirement\ExpenseRetirement;
use App\Imports\ExpenseRetirementJournalImport;
use App\ExpenseRetirement\ExpenseRetirementPayment;
use App\ExpenseRetirementJournal\ExpenseRetirementJournal;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // $bank_account = Account::select('accounts.account_name','accounts.id as id')->where('sub_account_type', 3)->distinct()->get();



        $requisitions = DB::table('requisitions')->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                                                 ->join('users','requisitions.user_id','users.id')
                                                 ->join('accounts','finance_supportive_details.account_id','accounts.id')
                                                 ->select('finance_supportive_details.amount_paid',
                                                 'requisitions.req_no','requisitions.created_at','requisitions.activity_name',
                                                 'requisitions.gross_amount','users.username as username','users.account_no as account_no',
                                                 'accounts.account_name as account', 'accounts.id as account_id')
                                                 ->where('requisitions.status', 'Paid')
                                                 ->where('requisitions.post_status', 'Not Posted')
                                                 // ->where('accounts.sub_account_type', 3)
                                                 ->groupBy('requisitions.req_no')
                                                 ->get();

        $retirements = Retirement::join('users','retirements.user_id','users.id')
                                   ->join('accounts','retirements.account_id','accounts.id')
                                   ->join('requisitions','retirements.req_no','requisitions.req_no')
                                   ->select(DB::raw("SUM(retirements.gross_amount)as total"),'retirements.*','users.username as staff','users.account_no as Account_No','accounts.account_name as Account_Name','requisitions.req_no', 'accounts.id as account_id')
                                   ->where('retirements.status', 'Confirmed')
                                   ->where('retirements.post_status', 'Not Posted')
                                   ->groupBy('retirements.id')
                                   ->distinct('retirements.ret_no')
                                   ->get();

        $expense_retirements = ExpenseRetirement::join('expense_retirement_payments','expense_retirements.ret_no','expense_retirement_payments.ret_no')
                              ->join('users','expense_retirements.user_id','users.id')
                              ->join('accounts','expense_retirement_payments.account_id','accounts.id')
                              ->select(DB::raw("SUM(expense_retirement_payments.amount_paid) as amount_paid"),'expense_retirements.*','users.username as username','users.account_no as account_no','accounts.account_name as account', 'accounts.id as account_id')
                              ->where('expense_retirements.status', 'Paid')
                              ->where('expense_retirements.post_status', 'Not Posted')
                              ->groupBy('expense_retirements.id')
                              ->get();
                           

        $bank_account = Account::where('sub_account_type', 3)->select('id','account_name')->get();
        $vat_account = Account::where('id',6)->select('account_name')->value('account_name');

        return view('reports.journals.create-journal', compact('bank_account','expense_retirements','vat_account'))->withRequisitions($requisitions)->withRetirements($retirements);
    }

    public function createRetirementJournal()
    {
        $retirements = Retirement::join('users','retirements.user_id','users.id')
                                   ->join('accounts','retirements.account_id','accounts.id')
                                   ->join('requisitions','retirements.req_no','requisitions.req_no')
                                   ->select(DB::raw("SUM(retirements.gross_amount)as total"),'retirements.*','users.username as staff','users.account_no as Account_No','accounts.account_name as Account_Name','requisitions.req_no', 'accounts.id as account_id')
                                   ->where('retirements.status', 'Confirmed')
                                   ->where('retirements.post_status', 'Not Posted')
                                   ->groupBy('retirements.id')
                                   ->distinct('retirements.ret_no')
                                   ->get();

        $expense_account = Account::where('sub_account_type', 8)->select('id','account_name')->get();
        $vat_account = Account::where('id',6)->select('account_name')->value('account_name');

        return view('reports.journals.create-retirement-journal', compact('expense_account','vat_account'))->withRetirements($retirements);                           
    }

    public function createExpenseRetirementJournal()
    {
        $expense_retirements = ExpenseRetirement::join('expense_retirement_payments','expense_retirements.ret_no','expense_retirement_payments.ret_no')
                              ->join('users','expense_retirements.user_id','users.id')
                              ->join('accounts','expense_retirement_payments.account_id','accounts.id')
                              ->select(DB::raw("SUM(expense_retirement_payments.amount_paid) as amount_paid"),'expense_retirements.*','users.username as username','users.account_no as account_no','accounts.account_name as account', 'accounts.id as account_id')
                              ->where('expense_retirements.status', 'Paid')
                              ->where('expense_retirements.post_status', 'Not Posted')
                              ->groupBy('expense_retirements.id')
                              ->get();
                           

        $bank_account = Account::where('sub_account_type', 3)->select('id','account_name')->get();
        $vat_account = Account::where('id',6)->select('account_name')->value('account_name');

        return view('reports.journals.create-expense-retirement-journal', compact('bank_account','expense_retirements','vat_account'));
    }

    public static function getDebitTotal($req_no)
    {

        $net = FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')
               ->where('finance_supportive_details.req_no',$req_no)
               ->sum('amount_paid');
        return $net;
    }

    public static function getCreditTotal($req_no)
    {

        $net = FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')
               ->where('finance_supportive_details.req_no',$req_no)
               ->sum('amount_paid');
        return -($net);
    }

    public static function getNetTotal($req_no)
    {
        $net = JournalController::getDebitTotal($req_no) + JournalController::getCreditTotal($req_no);
        return $net;
    }

    public function printJournal($journal_no)
    {
        $journal = DB::table('journals')->join('requisitions','requisitions.req_no','journals.req_no')
                     ->join('accounts','requisitions.account_id','accounts.id')->where('journals.journal_no',$journal_no)
                     ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                     ->join('users','requisitions.user_id','users.id')
                     ->select('journals.*','requisitions.*','accounts.account_name as account','users.account_no as account_no','users.username as username','finance_supportive_details.amount_paid as amount_paid')
                     ->groupBy('requisitions.req_no')
                     ->get();

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('fontDir/')
        ])->loadView('reports.journals.pdf.journal-pdf', compact('journal', 'journal_no'))->setPaper('a4', 'landscape');
        return $pdf->stream('journal-pdf');
    }

    public function printRetirementJournal($journal_no)
    {
        $retirement_journal = DB::table('retirements_journals')->join('retirements','retirements_journals.ret_no','retirements.ret_no')
                                ->join('accounts','retirements.account_id','accounts.id')
                                ->where('retirements_journals.journal_no',$journal_no)
                                ->join('users','retirements.user_id','users.id')
                                ->select('retirements_journals.*','retirements.*','accounts.account_name as Account_Name','users.account_no as Account_No')

                                ->groupBy('retirements.ret_no')
                                ->get();

        $vat_account = Account::where('id',6)->select('account_name')->value('account_name');                        

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('fontDir/')
        ])->loadView('reports.journals.pdf.retirement-journal-pdf', compact('retirement_journal','journal_no','vat_account'))->setPaper('a4', 'landscape');
        return $pdf->stream('journal-pdf');
    }

    public function printExpenseRetirementJournal($journal_no)
    {
        $expense_retirements = ExpenseRetirementJournal::join('expense_retirements','expense_retirement_journals.ret_no','expense_retirements.ret_no')
                              ->join('users','expense_retirements.user_id','users.id')
                              ->join('accounts','expense_retirements.account_id','accounts.id')
                              ->where('expense_retirement_journals.journal_no', $journal_no)
                              ->where('expense_retirements.post_status', 'Posted')
                              ->select('expense_retirements.*','expense_retirement_journals.*','users.username as username','users.account_no as account_no','accounts.account_name as account', 'accounts.id as account_id')
                              ->groupBy('expense_retirements.id')
                              ->get();

        $vat_account = Account::where('id',6)->select('account_name')->value('account_name');

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('fontDir/')
        ])->loadView('reports.journals.pdf.expense-retirement-journal-pdf', compact('expense_retirements','vat_account','journal_no'))->setPaper('a4', 'landscape');
        return $pdf->stream('journal-pdf');                      
    }

    public static function postJournal(Request $request)
    {
        $requisitions = DB::table('requisitions')->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                                                 ->join('users','requisitions.user_id','users.id')
                                                 ->join('accounts','requisitions.account_id','accounts.id')
                                                 ->select(DB::raw("SUM(finance_supportive_details.amount_paid) as amount_paid"),
                                                 'requisitions.req_no','requisitions.created_at','requisitions.activity_name',
                                                 'requisitions.gross_amount','users.username as username','users.account_no as account_no',
                                                 'accounts.account_name as account')
                                                 ->where('requisitions.status', 'Paid')
                                                 ->where('requisitions.post_status', 'Not Posted')
                                                 ->groupBy('requisitions.id')
                                                 ->distinct('req_no')
                                                 ->get();


        // $finance_supportive_details = DB::table('finance_supportive_details')->where('requisitions.post_status', 'Not Posted')->get();

        foreach ($requisitions as $requisition) {
            $journal = new Journal();
            $journal->journal_no = $request->journal_no;
            $journal->req_no = $requisition->req_no;
            $journal->status = 'Posted';
            $journal->save();

            $requisition = new Requisition();
            $requisition->where('requisitions.status', 'Paid')->where('requisitions.post_status', 'Not Posted')->update([
                'post_status' => 'Posted',
            ]);

        }

        alert()->success('Journal Posted Successfuly', 'Good Job')->persistent('close');
        return redirect()->back();
    }

    public static function postRetirementJournal(Request $request)
    {
        $retirements = Retirement::join('users','retirements.user_id','users.id')
                                   ->join('accounts','retirements.account_id','accounts.id')
                                   ->join('requisitions','retirements.req_no','requisitions.req_no')
                                   ->select(DB::raw("SUM(retirements.gross_amount)as total"),'retirements.*','users.username as staff','users.account_no as Account_No','accounts.account_name as Account_Name','requisitions.req_no', 'accounts.id as account_id')
                                   ->where('retirements.status', 'Confirmed')
                                   ->where('retirements.post_status', 'Not Posted')
                                   ->groupBy('retirements.id')
                                   ->distinct('retirements.ret_no')
                                   ->get();

        foreach ($retirements as $retirement) {
            $journal = new RetirementsJournal();
            $journal->journal_no = $request->journal_no;
            $journal->req_no = $retirement->req_no;
            $journal->ret_no = $retirement->ret_no;
            $journal->status = 'Posted';
            $journal->save();

            $retirement->where('retirements.status', 'Confirmed')->where('retirements.post_status', 'Not Posted')->update([
                'post_status' => 'Posted',
            ]);

        }

        alert()->success('Journal Posted Successfuly', 'Good Job')->persistent('close');
        return redirect()->back();
    }

    public static function postExpenseRetirementJournal(Request $request)
    {
        $expense_retirements = ExpenseRetirement::join('expense_retirement_payments','expense_retirements.ret_no','expense_retirement_payments.ret_no')
                              ->join('users','expense_retirements.user_id','users.id')
                              ->join('accounts','expense_retirements.account_id','accounts.id')
                              ->select(DB::raw("SUM(expense_retirement_payments.amount_paid) as amount_paid"),'expense_retirements.*','users.username as username','users.account_no as account_no','accounts.account_name as account', 'accounts.id as account_id')
                              ->where('expense_retirements.status', 'Paid')
                              ->where('expense_retirements.post_status', 'Not Posted')
                              ->groupBy('expense_retirements.id')
                              ->get();

        foreach ($expense_retirements as $retirement) {
            $journal = new ExpenseRetirementJournal();
            $journal->journal_no = $request->journal_no;
            $journal->ret_no = $retirement->ret_no;
            $journal->status = 'Posted';
            $journal->save();

            $retirement->where('expense_retirements.status', 'Paid')->where('expense_retirements.post_status', 'Not Posted')->update([
                'post_status' => 'Posted',
            ]);

        }

        alert()->success('Journal Posted Successfuly', 'Good Job')->persistent('close');
        return redirect()->back();
    }

    public static function generateJournalNo()
    {
        if (Journal::count() == 0) {
            $journal_no = "IMJ-1";
        }elseif (Journal::count() != 0) {
            $count = JournalController::getJournalCount();
            $journal_no = "IMJ-" . ($count + 1);
        }
        return $journal_no;
    }

    public static function generateRetirementJournalNo()
    {
        if (RetirementsJournal::count() == 0) {
            $journal_no = "RTJ-1";
        }elseif (RetirementsJournal::count() != 0) {
            $count = JournalController::getRetirementJournalCount();
            $journal_no = "RTJ-" . ($count + 1);
        }
        return $journal_no;
    }

    public static function generateExpenseRetirementJournalNo()
    {
        if (ExpenseRetirementJournal::count() == 0) {
            $journal_no = "ERTJ-1";
        }elseif (ExpenseRetirementJournal::count() != 0) {
            $count = JournalController::getExpenseRetirementJournalCount();
            $journal_no = "ERTJ-" . ($count + 1);
        }
        return $journal_no;
    }

    public static function getJournalCount()
    {
        return Journal::distinct('journal_no')->count('journal_no');
    }

    public static function getRetirementJournalCount()
    {
        return RetirementsJournal::distinct('journal_no')->count('journal_no');
    }

    public static function getExpenseRetirementJournalCount()
    {
        return ExpenseRetirementJournal::distinct('journal_no')->count('journal_no');
    }

    public static function getTotalofPaidRequisitions()
    {

        return FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')
                                        ->where('requisitions.status', 'Paid')
                                        ->where('requisitions.post_status', 'Not Posted')
                                        ->distinct()
                                        ->sum('finance_supportive_details.amount_paid');

    }

    public static function getTotalofJournalRequisitions()
    {
        return FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')
                                        ->where('requisitions.post_status', 'Posted')
                                        
                                        ->sum('amount_paid');
    }

    public static function getTotalofJournalRequisition($journal_no)
    {
        return FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')
                                        ->join('journals','requisitions.req_no','journals.req_no')
                                        ->where('requisitions.post_status', 'Posted')
                                        ->where('journals.journal_no', $journal_no)
                                        ->distinct()
                                        ->sum('amount_paid');
    }

    public static function getTotalofRetiredRequisitions()
    {
        return Retirement::where('status','Confirmed')
                         ->where('retirements.post_status', 'Not Posted')
                         ->sum('gross_amount');
    }

    public static function getTotalofExpenseRetirements()
    {
        return ExpenseRetirement::join('expense_retirement_payments','expense_retirements.ret_no','expense_retirement_payments.ret_no')
                                ->where('expense_retirements.status', 'Paid')
                                ->where('expense_retirements.post_status', 'Not Posted')
                                ->sum('amount_paid');
    }

    public static function getTotalofRetiredRequisitionsInJournals($journal_no)
    {
        return Retirement::join('retirements_journals','retirements.ret_no','retirements_journals.ret_no')
                         ->where('retirements.status','Confirmed')
                         ->where('retirements.post_status', 'Posted')
                         ->where('retirements_journals.journal_no', $journal_no)
                         ->sum('gross_amount');
    }

    public static function getTotalofExpenseRetirementInJournals()
    {
        return ExpenseRetirement::where('expense_retirements.status','Paid')
                         ->where('expense_retirements.post_status', 'Posted')
                         ->sum('gross_amount');
    }

    public static function getTotalofExpenseRetirementInJournal($journal_no)
    {
        return ExpenseRetirement::join('expense_retirement_journals','expense_retirements.ret_no','expense_retirement_journals.ret_no')
                         ->join('expense_retirement_payments','expense_retirements.ret_no','expense_retirement_payments.ret_no')
                         ->where('expense_retirements.status','Paid')
                         ->where('expense_retirements.post_status', 'Posted')
                         ->where('expense_retirement_journals.journal_no', $journal_no)
                         ->distinct()
                         ->sum('amount_paid');
    }

    public function viewJournals()
    {
        $journals = DB::table('journals')->distinct('journal_no')->get();
        return view('reports.journals.view-journal')->withJournals($journals->unique('journal_no'));
    }

    public function viewRetirementJournals()
    {
        $journals = RetirementsJournal::distinct('journal_no')->get();
        return view('reports.journals.view-retirement-journal')->withJournals($journals->unique('journal_no'));
    }

    public function viewExpenseRetirementJournals()
    {
        $journals = ExpenseRetirementJournal::distinct('journal_no')->get();
        return view('reports.journals.view-expense-retirement-journal')->withJournals($journals->unique('journal_no'));
    }

    public function viewJournalEntry($journal_no)
    {
        $journal = DB::table('journals')->join('requisitions','requisitions.req_no','journals.req_no')
                     ->where('journals.journal_no',$journal_no)
                     ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                     ->join('accounts','finance_supportive_details.account_id','accounts.id')
                     ->join('users','requisitions.user_id','users.id')
                     ->select('journals.*','requisitions.*','accounts.account_name as account','users.account_no as account_no','users.username as username','finance_supportive_details.amount_paid as amount_paid')
                     ->groupBy('requisitions.req_no')
                     ->get();

        return view('reports.journals.journal-entry', compact('retirement_journal','journal_no'))->withJournal($journal);
    }

    public static function viewRetirementJournalsEntry($journal_no)
    {
        $retirement_journal = DB::table('retirements_journals')->join('retirements','retirements_journals.ret_no','retirements.ret_no')
                                ->join('accounts','retirements.account_id','accounts.id')
                                ->where('retirements_journals.journal_no',$journal_no)
                                ->join('users','retirements.user_id','users.id')
                                ->select('retirements_journals.*','retirements.*','accounts.account_name as Account_Name','users.account_no as Account_No')

                                ->groupBy('retirements.ret_no')
                                ->get();

        $expense_account = Account::where('sub_account_type', 8)->select('id','account_name')->get();
        $vat_account = Account::where('id',6)->select('account_name')->value('account_name');                        

        return view('reports.journals.retirement-journal-entry', compact('retirement_journal','journal_no','vat_account','expense_account'));
    }

    public static function viewExpenseRetirementJournalsEntry($journal_no)
    {
        $expense_retirements = ExpenseRetirementJournal::join('expense_retirements','expense_retirement_journals.ret_no','expense_retirements.ret_no')
                              ->join('users','expense_retirements.user_id','users.id')
                              ->join('accounts','expense_retirements.account_id','accounts.id')
                              ->where('expense_retirement_journals.journal_no', $journal_no)
                              ->where('expense_retirements.post_status', 'Posted')
                              ->select('expense_retirements.*','expense_retirement_journals.*','users.username as username','users.account_no as account_no','accounts.account_name as account', 'accounts.id as account_id')
                              ->groupBy('expense_retirements.id')
                              ->get();

        $vat_account = Account::where('id',6)->select('account_name')->value('account_name');                      

        return view('reports.journals.expense-retirement-journal-entry', compact('expense_retirements','journal_no','vat_account'));
    }


    public static function getSumOfRetirement($journal_no)
    {
        return DB::table('retirements')->join('retirements_journals','retirements.ret_no','retirements_journals.ret_no')
                                       ->where('retirements_journals.journal_no',$journal_no)
                                       ->sum('retirements.gross_amount');
    }

    public function exportImprestJournal($journal_no)
    {
        return Excel::download(new ImprestJournalImport, 'unretired-imprest-journal-report.xlsx');
    }

    public function exportRetirementJournal($journal_no)
    {
        return Excel::download(new RetirementJournalImport, 'retirement-journal-report.xlsx');
    }

    public function exportExpenseRetirementJournal($journal_no)
    {
        return Excel::download(new ExpenseRetirementJournalImport, 'expense-retirement-journal-report.xlsx');
    }

    public function updateExpenseRetiremenBankAccount($ret_no, $account_id)
    {
        $result = ExpenseRetirementPayment::where('ret_no', $ret_no)->update([
            'account_id' => $account_id,
        ]);

        return response()->json(['result' => $result]);
    }

    public function updateImprestBankAccount($req_no, $account_id)
    {
        $result = FinanceSupportiveDetail::where('req_no', $req_no)->update([
            'account_id' => $account_id,
        ]);

        return response()->json(['result' => $result]);
    }

    public function updateRetirementBankAccount($ret_no, $account_id)
    {
        $result = Retirement::where('ret_no', $ret_no)->update([
            'account_id' => $account_id,
        ]);

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
}
