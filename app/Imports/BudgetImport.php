<?php

namespace App\Imports;

use App\Budget\Budget;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BudgetImport implements FromView, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function view(): View
    {
        libxml_use_internal_errors(true);
        $budget = Budget::findOrFail(request('budget_id'));
        $budgets = Budget::join('items','budgets.id','items.budget_id')
                   ->select('items.*')
                   ->where('budgets.status', 'Confirmed')
                   ->where('items.budget_id', request('budget_id'))
                   ->get();
        libxml_clear_errors();           
        return view('reports.budgets.budget-report-pdf', compact('budget','budgets','budget_id'));
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function headings(): array
    {
        return [
            'Item No.',
            'Item Name',
            'Total Allocated',
            'Amount Uncommitted',
            'Amount Committed',
            'Amount Spent',
            'Gross Spent'
        ];
    }
}
