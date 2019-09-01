<?php
use App\Http\Controllers\Journal\JournalController;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Journal Report</title>
    <!-- App CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<p class="lead">Expense Retirements Journal Report</p>
<table class="table table-sm table-striped table-bordered table-condensed">
    <thead style="max-width: 30px;">
        <tr>
            <th scope="col" class="text-center">Date</th>
            <th scope="col" class="text-center">Ret #.</th>
            <th scope="col" class="text-center">User</th>
            <th scope="col" class="text-center">Item Name</th>
            <th scope="col" class="text-center">Supplier</th>
            <th scope="col" class="text-center">GL Accounts</th>
            <th scope="col" class="text-center">VAT</th>
            <th scope="col" class="text-center">DR</th>
            <th scope="col" class="text-center">CR</th>
        </tr>
    </thead> 
    <tbody>
        @foreach($expense_retirements as $ret)
        <tr>

            <td scope="col" style="width: 90px" class="text-center">{{date('Y-m-d',strtotime($ret->created_at))}}</td>
            <td scope="col" style="width: 80px" class="text-center">{{$ret->ret_no}}</td>
            <td scope="col" class="text-center">{{$ret->username}}</td>
            <td scope="col" class="text-left">{{$ret->item_name}}</td>
            <td scope="col" class="text-left">{{$ret->supplier_id}}</td>
            <td scope="col" class="text-left">
                {{$ret->account}}
            </td>
            <td scope="col" class="text-left"></td>

            @if($ret->vat == 'VAT Inclusive')
            <td scope="col" class="text-right">
                {{number_format($ret->gross_amount/1.18,2)}}
            </td>
            @elseif($ret->vat == 'VAT Exclusive')
            <td scope="col" class="text-right">
                {{number_format($ret->gross_amount - $ret->vat_amount,2)}}
            </td>
            @else
            <td scope="col" class="text-right">
                {{number_format($ret->gross_amount,2)}}
            </td>
            @endif

            
            <td></td>                                                       
        </tr>
        @if($ret->vat != 'Non VAT')
        <tr>
            <td scope="col" style="width: 90px" class="text-center">{{date('Y-m-d',strtotime($ret->created_at))}}</td>
            <td scope="col" style="width: 80px" class="text-center">{{$ret->ret_no}}</td>
            <td scope="col" class="text-center">{{$ret->username}}</td>
            <td scope="col" class="text-left">{{$ret->item_name}}</td>
            <td scope="col" class="text-left">{{$ret->supplier_id}}</td>
            <td scope="col" class="text-left">
                {{$vat_account}}
            </td>
            <td scope="col" class="text-left">{{$ret->vat}}</td>
            <td scope="col" class="text-right">{{number_format($ret->vat_amount,2)}}</td> 
            <td></td>
        </tr>
        @endif
        <tr>
            <td scope="col" style="width: 90px" class="text-center">{{date('Y-m-d',strtotime($ret->created_at))}}</td>
            <td scope="col" style="width: 80px" class="text-center">{{$ret->ret_no}}</td>
            <td scope="col" class="text-center">{{$ret->username}}</td>
            <td scope="col" class="text-left">{{$ret->item_name}}</td>
            <td scope="col" class="text-left">{{$ret->supplier_id}}</td>
            <td scope="col" class="text-left">{{$ret->account_no}}</td>
            <td></td>
            <td></td>
            @if($ret->vat == 'VAT Inclusive')
            <td scope="col" class="text-right">
                {{number_format($ret->gross_amount/1.18,2)}}
            </td>
            @elseif($ret->vat == 'VAT Exclusive')
            <td scope="col" class="text-right">
                {{number_format($ret->gross_amount,2)}}
            </td>
            @else
            <td scope="col" class="text-right">
                {{number_format($ret->gross_amount,2)}}
            </td>
            @endif
            
        </tr>
@endforeach
        
        
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <!-- <td></td> -->
            <td scope="col" class="text-center"></td>
            <td scope="col" class="text-center font-weight-bold">Total</td>
            <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofExpenseRetirementInJournal($journal_no),2)}}</td>
            <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofExpenseRetirementInJournal($journal_no),2)}}</td> 
        </tr>    
</tbody>
</table>
</body>
</html>

