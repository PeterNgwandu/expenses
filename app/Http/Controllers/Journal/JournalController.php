<?php

namespace App\Http\Controllers\Journal;

use DB;
use PDF;
use App\Journal\Journal;
use Illuminate\Http\Request;
use App\Retirements\Retirement;
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

        // $requisitions = DB::table('finance_supportive_details')->join('requisitions','finance_supportive_details.req_no','requisitions.req_no')->join('users','requisitions.user_id','users.id')->join('sub_account_types','users.sub_acc_type_id','sub_account_types.id')->join('accounts','requisitions.account_id','accounts.id')->select(DB::raw("SUM(finance_supportive_details.amount_paid) as amount_paid"),'requisitions.req_no','requisitions.activity_name','users.username as username','users.account_no as account_no')->groupBy('requisitions.id')->groupBy('requisitions.user_id')->groupBy('requisitions.account_id')->groupBy('requisitions.req_no')->distinct('requisitions.req_no')->get();

        $requisitions = DB::table('requisitions')->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')->join('users','requisitions.user_id','users.id')->join('accounts','requisitions.account_id','accounts.id')->select(DB::raw("SUM(finance_supportive_details.amount_paid) as amount_paid"),'requisitions.*','users.username as username','users.account_no as account_no','accounts.account_name as account')->groupBy('requisitions.id')->distinct('req_no')->get();

        // $retirements = DB::table('retirements')->join('requisitions','retirements.req_no','requisitions.req_no')->join('users','requisitions.user_id','users.id')->join('accounts','requisitions.account_id','accounts.id')->select(DB::raw("SUM((retirements.quantity * retirements.unit_price) * 1.18) as amount_paid"),DB::raw("SUM(retirements.vat_amount) as vat"),'requisitions.req_no','users.username as username','accounts.account_name as account')->groupBy('requisitions.id')->distinct('req_no')->get();

        // $retirements = DB::table('retirements')->join('requisitions','retirements.req_no','requisitions.req_no')->join('users','retirements.user_id','users.id')->join('sub_account_types','users.sub_acc_type_id','sub_account_types.id')->join('accounts','retirements.account_id','accounts.id')->select('retirements.created_at','retirements.ret_no','retirements.supplier_id','retirements.item_name','retirements.description','users.username as username','users.account_no as account_no','accounts.account_name as account', DB::raw("SUM((requisitions.quantity * requisitions.unit_price) * 1.18) as amount_paid"),DB::raw("SUM(retirements.vat_amount) as vat"))->where('retirements.status','Confirmed')->groupBy('retirements.id')->distinct()->get();

        $retirements = DB::select(DB::raw("SELECT retirements.*, SUM(retirements.gross_amount),ret_no,item_name FROM `retirements` GROUP BY retirements.id"));



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
        $requisitions = DB::table('requisitions')->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')->join('users','requisitions.user_id','users.id')->join('accounts','requisitions.account_id','accounts.id')->select(DB::raw("SUM(finance_supportive_details.amount_paid) as amount_paid"),'requisitions.*','users.username as username','users.account_no as account_no','accounts.account_name as account')->groupBy('requisitions.id')->distinct('req_no')->get();

        $retirements = DB::table('retirements')->join('requisitions','retirements.req_no','requisitions.req_no')->join('users','retirements.user_id','users.id')->join('sub_account_types','users.sub_acc_type_id','sub_account_types.id')->join('accounts','retirements.account_id','accounts.id')->select('retirements.created_at','retirements.ret_no','retirements.supplier_id','retirements.item_name','retirements.description','users.username as username','users.account_no as account_no','accounts.account_name as account', DB::raw("SUM((retirements.quantity * retirements.unit_price) * 1.18) as amount_paid"),DB::raw("SUM(retirements.vat_amount) as vat"))->where('retirements.status','Confirmed')->groupBy('retirements.id')->distinct()->get();


        //JournalController::postJournal($request);


        $pdf = PDF::loadView('journals.journal-pdf', compact('requisitions','retirements'));
        return $pdf->stream('journal-pdf');
    }

    public static function postJournal(Request $request)
    {
        $journal = new Journal();
        $journal->journal_no = JournalController::generateJournalNo();
        $journal->req_no = $request->req_no;
        $journal->status = 'Posted';
        $journal->save();

        $finance_supportive_details = DB::table('finance_supportive_details')
                                      ->where('req_no', $journal->req_no)
                                      ->update([
                                        'status' => 'Posted'
                                      ]);
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
        return Journal::count();
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
