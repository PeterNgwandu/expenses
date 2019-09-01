<?php

namespace App\Imports;

use DB;
use App\Accounts\Account;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\ExpenseRetirementJournal\ExpenseRetirementJournal;

class ExpenseRetirementJournalImport implements FromView
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
        $expense_retirements = ExpenseRetirementJournal::join('expense_retirements','expense_retirement_journals.ret_no','expense_retirements.ret_no')
                              ->join('users','expense_retirements.user_id','users.id')
                              ->join('accounts','expense_retirements.account_id','accounts.id')
                              ->where('expense_retirement_journals.journal_no', $journal_no)
                              ->where('expense_retirements.post_status', 'Posted')
                              ->select('expense_retirements.*','expense_retirement_journals.*','users.username as username','users.account_no as account_no','accounts.account_name as account', 'accounts.id as account_id')
                              ->groupBy('expense_retirements.id')
                              ->get();

        $vat_account = Account::where('id',6)->select('account_name')->value('account_name');                      

        return view('reports.journals.excels.expense-retirement-journal-excel', compact('expense_retirements','journal_no','vat_account'));
    }
}
