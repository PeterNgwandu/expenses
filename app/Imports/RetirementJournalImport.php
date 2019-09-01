<?php

namespace App\Imports;

use DB;
use App\Accounts\Account;
use App\Journal\RetirementsJournal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\FromCollection;

class RetirementJournalImport implements FromView
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function view(): View
    {
        libxml_use_internal_errors(true);
        $journal_no = request('journal_no');
        $retirement_journal = DB::table('retirements_journals')->join('retirements','retirements_journals.ret_no','retirements.ret_no')
                                ->join('accounts','retirements.account_id','accounts.id')
                                ->where('retirements_journals.journal_no',$journal_no)
                                ->join('users','retirements.user_id','users.id')
                                ->select('retirements_journals.*','retirements.*','accounts.account_name as Account_Name','users.account_no as Account_No')

                                ->groupBy('retirements.ret_no')
                                ->get();

        $expense_account = Account::where('sub_account_type', 8)->select('id','account_name')->get();
        $vat_account = Account::where('id',6)->select('account_name')->value('account_name');                        
        return view('reports.journals.excels.retirement-journal-excel', compact('retirement_journal','journal_no','vat_account','expense_account'));                        
    }
}
