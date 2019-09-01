<?php

namespace App\Imports;

use App\Retirement\Retirement;
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\FromCollection;

class ImprestImport implements FromView, WithHeadingRow
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

        $unretired_imprest_dates = Requisition::join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                             ->select('finance_supportive_details.payment_date')
                             ->where('requisitions.status', 'Paid')
                             ->whereNotIn('requisitions.req_no', $req_no->toArray())
                             ->get()
                             ->pluck('payment_date');                     

        $myArray = $unretired_imprest_dates->toArray();
        // $from = reset($myArray);
        // $to = end($myArray);
        libxml_clear_errors();
        return view('reports.imprests.unretired-imprests-report-pdf', compact('req_no','unretired_imprest','from','to'));
    }
}
