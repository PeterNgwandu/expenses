<?php

namespace App\Http\Controllers\Journal;

use DB;
use PDF;
use App\Journal\Journal;
use App\Journal\RetirementsJournal;
use Illuminate\Http\Request;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use App\Http\Controllers\Controller;
use App\Accounts\FinanceSupportiveDetail;

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

        // $requisitions = FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')
        //                                        ->join('users','requisitions.user_id','users.id')
        //                                        ->join('accounts','requisitions.account_id','accounts.id')
        //                                        ->where('requisitions.post_status','Not Posted')
        //                                        ->where('requisitions.status', 'Paid')
        //                                        ->select('finance_supportive_details.*','requisitions.req_no','users.username','users.account_no as account_no','accounts.account_name as account')
        //                                        ->distinct('finance_supportive_details.id')
        //                                        ->distinct('finance_supportive_details.req_no')
        //                                        ->distinct('finance_supportive_details.created_at')
        //                                        ->groupBy('requisitions.id')->get();

        $retirements = Retirement::join('users','retirements.user_id','users.id')
                                   ->join('accounts','retirements.account_id','accounts.id')
                                   ->join('requisitions','retirements.req_no','requisitions.req_no')
                                   ->select(DB::raw("SUM(retirements.gross_amount)as total"),'retirements.*','users.username as staff','users.account_no as Account_No','accounts.account_name as Account_Name','requisitions.req_no')
                                   ->where('retirements.status', 'Confirmed')
                                   ->where('retirements.post_status', 'Not Posted')
                                   ->groupBy('retirements.id')
                                   ->distinct('retirements.ret_no')
                                   ->get();



        return view('journals.create-journal')->withRequisitions($requisitions)->withRetirements($retirements);
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

    public function printJournal(Request $request)
    {
        // $requisitions = DB::table('requisitions')->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
        //                                          ->join('users','requisitions.user_id','users.id')
        //                                          ->join('accounts','requisitions.account_id','accounts.id')
        //                                          ->select(DB::raw("SUM(finance_supportive_details.amount_paid) as amount_paid"),
        //                                          'requisitions.req_no','requisitions.created_at','requisitions.activity_name',
        //                                          'requisitions.gross_amount','users.username as username','users.account_no as account_no',
        //                                          'accounts.account_name as account')
        //                                          ->where('requisitions.status', 'Paid')
        //                                          ->where('requisitions.post_status', 'Not Posted')
        //                                          ->groupBy('requisitions.id')
        //                                          ->distinct('req_no')
        //                                          ->get();
        $requisitions = FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')->join('users','requisitions.user_id','users.id')->join('accounts','requisitions.account_id','accounts.id')->where('requisitions.status','Not Posted')->select('finance_supportive_details.*','requisitions.req_no','users.username','users.account_no as account_no','accounts.account_name as account')->distinct('finance_supportive_details.id')->distinct('finance_supportive_details.req_no')->distinct('finance_supportive_details.created_at')->get();

        $retirements = Retirement::join('users','retirements.user_id','users.id')
                                   ->join('accounts','retirements.account_id','accounts.id')
                                   ->join('requisitions','retirements.req_no','requisitions.req_no')
                                   ->select(DB::raw("SUM(retirements.gross_amount)as total"),'retirements.*','users.username as staff','users.account_no as Account_No','accounts.account_name as Account_Name','requisitions.req_no')
                                   ->where('retirements.status', 'Confirmed')
                                   ->where('retirements.post_status', 'Not Posted')
                                   ->groupBy('retirements.id')
                                   ->distinct('retirements.ret_no')
                                   ->get();

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('fontDir/')
        ])->loadView('journals.journal-pdf', compact('requisitions','retirements'));
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

        $retirements = Retirement::join('users','retirements.user_id','users.id')
                                   ->join('accounts','retirements.account_id','accounts.id')
                                   ->join('requisitions','retirements.req_no','requisitions.req_no')
                                   ->select(DB::raw("SUM(retirements.gross_amount)as total"),'retirements.*','users.username as staff','users.account_no as Account_No','accounts.account_name as Account_Name','requisitions.req_no')
                                   ->where('retirements.status', 'Confirmed')
                                   ->where('retirements.post_status', 'Not Posted')
                                   ->groupBy('retirements.id')
                                   ->distinct('retirements.ret_no')
                                   ->get();

        $finance_supportive_details = DB::table('finance_supportive_details')->where('requisitions.status', 'Not Posted')->get();

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

        foreach ($retirements as $retirement) {
            $journal = new RetirementsJournal();
            $journal->journal_no = $request->journal_no;
            $journal->ret_no = $retirement->ret_no;
            $journal->req_no = $retirement->req_no;
            $journal->status = 'Posted';
            $journal->save();

            $retirement->where('retirements.status', 'Confirmed')->where('retirements.post_status', 'Not Posted')->update([
                'post_status' => 'Posted',
            ]);
        }

        // foreach ($finance_supportive_details as $payment) {
        //     $finance_supportive_detail = new FinanceSupportiveDetail();
        //     $finance_supportive_detail->where('finance_supportive_details.status', 'Not Posted')->update([
        //         'status' => 'Posted',
        //     ]);
        // }

        alert()->success('Journal Posted Successfuly', 'Good Job')->persistent('close');
        return redirect()->back();
    }

    public static function generateJournalNo()
    {
        if (Journal::count() == 0) {
            $journal_no = "JE-1";
        }elseif (Journal::count() != 0) {
            $count = JournalController::getJournalCount();
            $journal_no = "JE-" . ($count + 1);
        }
        return $journal_no;
    }

    public static function getJournalCount()
    {
        return Journal::distinct('journal_no')->count('journal_no');
    }

    public static function getTotalofPaidRequisitions()
    {
        // return FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')
        //                                 ->where('finance_supportive_details.status','!=', 'Posted')
        //                                 ->where('requisitions.status', 'Paid')
        //                                 ->where('requisitions.post_status', 'Not Posted')
        //                                 ->sum('amount_paid');
        return FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')->where('requisitions.status', 'Not Posted')->sum('finance_supportive_details.amount_paid');

    }

    public static function getTotalofJournalRequisitions()
    {
        return FinanceSupportiveDetail::join('requisitions','finance_supportive_details.req_no','requisitions.req_no')
                                        ->where('requisitions.post_status', 'Posted')
                                        ->sum('amount_paid');
    }

    public static function getTotalofRetiredRequisitions()
    {
        return Retirement::where('status','Confirmed')
                         ->where('retirements.post_status', 'Not Posted')
                         ->sum('gross_amount');
    }

    public static function getTotalofRetiredRequisitionsInJournals($journal_no)
    {
        return Retirement::join('retirements_journals','retirements.ret_no','retirements_journals.ret_no')
                         ->where('retirements.status','Confirmed')
                         ->where('retirements.post_status', 'Posted')
                         ->where('retirements_journals.journal_no', $journal_no)
                         ->sum('gross_amount');
    }

    public function viewJournals()
    {
        $journals = DB::table('journals')->join('retirements_journals','journals.req_no','retirements_journals.req_no')->distinct('journal_no')->get();
        return view('journals.view-journal')->withJournals($journals->unique('journal_no'));
    }

    public function viewJournalEntry($journal_no)
    {
        $journal = DB::table('journals')->join('requisitions','requisitions.req_no','journals.req_no')
                     ->join('accounts','requisitions.account_id','accounts.id')->where('journals.journal_no',$journal_no)
                     ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                     ->join('users','requisitions.user_id','users.id')
                     ->select('journals.*','requisitions.*','accounts.account_name as account','users.account_no as account_no','users.username as username','finance_supportive_details.amount_paid as amount_paid')
                     ->get();

        $retirement_journal = DB::table('retirements_journals')->join('retirements','retirements_journals.ret_no','retirements.ret_no')
                                ->join('accounts','retirements.account_id','accounts.id')
                                ->where('retirements_journals.journal_no',$journal_no)
                                ->join('users','retirements.user_id','users.id')
                                ->select('retirements_journals.*','retirements.*','accounts.account_name as Account_Name','users.account_no as Account_No')

                                ->distinct('retirements.ret_no')
                                ->get();

        return view('journals.journal-entry', compact('retirement_journal','journal_no'))->withJournal($journal);
    }

    public static function getSumOfRetirement($journal_no)
    {
        return DB::table('retirements')->join('retirements_journals','retirements.ret_no','retirements_journals.ret_no')
                                       ->where('retirements_journals.journal_no',$journal_no)
                                       ->sum('retirements.gross_amount');
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
