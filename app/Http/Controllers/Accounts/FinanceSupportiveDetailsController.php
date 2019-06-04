<?php

namespace App\Http\Controllers\Accounts;

use DB;
use Alert;
use Illuminate\Http\Request;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use App\Http\Controllers\Controller;
use App\Accounts\FinanceSupportiveDetail;

class FinanceSupportiveDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //     'req_id' => 'required',
        //     'staff_id' => 'required',
        //     'account_id' => 'account_id',
        //     'ref_no' => 'required',
        //     'comment' => 'required',
        //     'payment_date' => 'required',
        // ]);

        $amount_requested = Requisition::where('req_no', $request->req_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->sum('gross_amount');
        $current_paid_amount = FinanceSupportiveDetail::where('req_no', $request->req_no)->sum('amount_paid');
        $amount_remained_to_pay = $amount_requested - $current_paid_amount;

        if ($amount_remained_to_pay < $request->amount_paid)
        {
            alert()->error('You cannot pay more than requested amount', 'Ooops! Error')->persistent('Close');
            return redirect()->back();
        }

        $financeSupportiveDetails = new FinanceSupportiveDetail();
        $financeSupportiveDetails->req_no = $request->req_no;
        // $financeSupportiveDetails->serial_no = $request->serial_no;
        $financeSupportiveDetails->cash_collector = $request->cash_collector;
        $financeSupportiveDetails->amount_paid = $request->amount_paid;
        $financeSupportiveDetails->account_id = $request->account_id;
        $financeSupportiveDetails->ref_no = $request->ref_no;
        $financeSupportiveDetails->comment = $request->comment;
        $financeSupportiveDetails->payment_date = $request->payment_date;

        if ($amount_remained_to_pay < $request->amount_paid)
        {
            alert()->error('You cannot pay more than requested amount', 'Ooops! Error')->persistent('Close');
            return redirect()->back();
        }

        if ($amount_remained_to_pay < $request->amount_paid) {
            alert()->error('You cannot pay more than requested amount', 'Ooops! Error')->persistent('Close');
            return redirect()->back();
        }else{
            alert()->error('You have successfuly paid ' . number_format($request->amount_paid) , 'Good Job')->persistent('Close');
            $financeSupportiveDetails->save();

        }


        $requisition = Requisition::where('req_no',$request->req_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->get();
        foreach ($requisition as $requisition) {
            if ($requisition->status == 'Confirmed') {
                $result = DB::table('requisitions')->where('req_no', $requisition->req_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->update([
                    'status' => "Paid"
                ]);
                session()->flash('message', 'Finance Supportive Details added');
                return redirect(url('confirmation-form/'.$request->req_no));
            }
        }


        if ($requisition->status == 'Paid') {
            session()->flash('message', 'Finance Supportive Details added');
            return redirect(url('/paid-requisitions'));
        }

        return redirect(url('confirmation-form/'.$request->req_no));
    }

    public function receiveReturnPayments(Request $request)
    {
        $req_no = $request->req_no;

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

        if($amount_claimed > 0)
        {
            $status = 'Return';
        }elseif($amount_unretired > 0)
        {
            $status = 'Receive';
        }

        $financeSupportiveDetails = new FinanceSupportiveDetail();
        $financeSupportiveDetails->req_no = $request->req_no;
        $financeSupportiveDetails->cash_collector = $request->cash_collector;
        $financeSupportiveDetails->amount_paid = $request->amount_paid;
        $financeSupportiveDetails->account_id = $request->account_id;
        $financeSupportiveDetails->ref_no = $request->ref_no;
        $financeSupportiveDetails->comment = $request->comment;
        $financeSupportiveDetails->payment_date = $request->payment_date;
        $financeSupportiveDetails->status = $status;
        $financeSupportiveDetails->save();

        $lastRow = FinanceSupportiveDetail::join('users','requisitions.user_id','users.id')
                                          ->join('accounts','finance_supportive_details.account_id','accounts.id')
                                          ->select('finance_supportive_details.status','finance_supportive_details.req_no','finance_supportive_details.comment', 'cash_collector', 'amount_paid', 'finance_supportive_details.ref_no','accounts.account_name as account','users.username as username')
                                          ->orderBy('finance_supportive_details.created_at', 'desc')
                                          ->first();

        return view('retirements.payments-confirmation-form', compact('lastRow'));

    }

    public static function generateReferenceNo()
    {
        $ref_no = null;
        if(FinanceSupportiveDetailsController::getLatestRefNo() == null)
        {
            $ref_no = 'REQ-PAID-1';
        }elseif (FinanceSupportiveDetailsController::getLatestRefNo() != null) {
            $ref_no_count = FinanceSupportiveDetailsController::getLatestRefNoCount();
            $ref_no = 'REQ-PAID-'.($ref_no_count + 1);
        }
        return $ref_no;
    }

    public static function getLatestRefNo()
    {
        return FinanceSupportiveDetail::select('ref_no')->latest()->first();

    }

    public static function getLatestRefNoCount()
    {
        return FinanceSupportiveDetail::select('ref_no')->distinct()->count();
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

    public static function amountPaid($req_no)
    {
        return FinanceSupportiveDetail::where('req_no', $req_no)->sum('amount_paid');
    }

    public static function balanceRemained($req_no)
    {

    }

}
