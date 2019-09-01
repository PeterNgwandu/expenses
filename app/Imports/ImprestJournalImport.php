<?php

namespace App\Imports;

use DB;
use App\Journal\Journal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\FromCollection;

class ImprestJournalImport implements FromView
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function view(): View
    {
        $journal_no = request('journal_no');
        libxml_use_internal_errors(true);
        $journal = DB::table('journals')->join('requisitions','requisitions.req_no','journals.req_no')
                     ->where('journals.journal_no',$journal_no)
                     ->join('finance_supportive_details','requisitions.req_no','finance_supportive_details.req_no')
                     ->join('accounts','finance_supportive_details.account_id','accounts.id')
                     ->join('users','requisitions.user_id','users.id')
                     ->select('journals.*','requisitions.*','accounts.account_name as account','users.account_no as account_no','users.username as username','finance_supportive_details.amount_paid as amount_paid')
                     ->groupBy('requisitions.req_no')
                     ->get();
        // libxml_clear_errors();
        return view('reports.journals.excels.imprest-journal-excel', compact('journal_no'))->withJournal($journal);            
    }
}
