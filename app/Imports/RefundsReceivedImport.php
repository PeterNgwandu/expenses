<?php

namespace App\Imports;
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\FromCollection;

class RefundsReceivedImport implements FromView
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function view(): View
    {
        $from = Carbon::parse(request('from'));
        $to = Carbon::parse(request('to'));
        libxml_use_internal_errors(true);

        if(!empty(request('req_no'))){
            $balance_received = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                          ->join('users','requisitions.user_id','users.id')
                          ->join('departments','users.department_id','departments.id')
                          ->select('requisitions.req_no','requisitions.activity_name','finance_supportive_details.amount_paid','users.username as username','departments.name as department','requisitions.created_at','finance_supportive_details.payment_date')
                          ->where('finance_supportive_details.status','Receive')
                          // ->where('requisitions.req_no', request('req_no'))
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
        }else{

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
        }                 

        return view('reports.refunds_received.excels.refunds_received_excel', compact('balance_received','balance_received_dates','balance_received_req_no','from','to'));
    }
}
