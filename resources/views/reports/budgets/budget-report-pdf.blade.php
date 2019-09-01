<?php
use App\Http\Controllers\Reports\ReportsController;

?>
<!DOCTYPE html>
<html>
<head>
	<title>Budget Summary Report</title>
	<!-- App CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<p class="lead">Report for {{$budget->title}} </p>
<table  class="table table-sm table-bordered mb-0">
<thead>
    <tr>
        <th scope="col" class="text-center">Item No.</th>
        <th scope="col" class="text-center">Item Name</th>
        <th scope="col" class="text-center">Total Allocated</th>
        <th scope="col" class="text-center">Amount Uncommitted</th>
        <th scope="col" class="text-center">Amount Committed</th>
        <th scope="col" class="text-center">Amount Not Spent</th>
        <th scope="col" class="text-center">Amount Spent</th>
        <th scope="col" class="text-center">VAT</th>
        <th scope="col" class="text-center">Gross Spent</th>
</tr>
</thead>
<tbody>
   @foreach($budgets as $budget)
    <tr>
        <td style="width: 30px;" class="align-middle text-left">{{$budget->item_no}}</td>
        <td style="width: 220px;" class="align-middle text-left">{{$budget->item_name}}</td>
        <td class="align-middle text-right">
            {{number_format($budget->unit_price * $budget->quantity,2)}}
        </td>
        <td class="text-right">
            {{number_format(ReportsController::calculateUncommitedAmount($budget->budget_id,$budget->id),2)}}
        </td>
        <td class="align-middle text-right">
            @if(number_format(ReportsController::calculateCommittedAmount($budget->budget_id,$budget->id) > 0))
                {{number_format(ReportsController::calculateCommittedAmount($budget->budget_id,$budget->id),2)}}
            @else
                {{number_format(0,2)}}
            @endif    
        </td>
        <td class="align-middle text-right">
            @if(ReportsController::calculateNotSpentAmount($budget->budget_id,$budget->id) > 0)
                {{number_format(ReportsController::calculateNotSpentAmount($budget->budget_id,$budget->id),2)}}
            @else
                {{number_format(0,2)}}
            @endif        
            
        </td>
        <td class="align-middle text-right">
            @if(ReportsController::calculateAmountSpent($budget->budget_id,$budget->id) > 0)
                {{number_format(ReportsController::calculateAmountSpent($budget->budget_id,$budget->id),2)}}
            @else
                {{number_format(0,2)}}
            @endif        
        </td>

        <td class="align-middle text-right">
            {{number_format(ReportsController::calculateVATByItem($budget->budget_id,$budget->id),2)}}
        </td>


        
        <td class="text-right">
            {{number_format(ReportsController::calculateGrossSpent($budget->budget_id,$budget->id),2)}}
        </td>
    </tr>
   @endforeach 
    <tr>
        <td></td>
        <td class="font-weight-bold text-right">Total</td>
        <td class="text-right">
            {{number_format(ReportsController::totalBudgetById($budget->budget_id),2)}}
        </td>
        <td class="text-right">
            {{number_format(ReportsController::totalUncommitted($budget->budget_id),2)}}
        </td>
        <td class="text-right">
            {{number_format(ReportsController::totalCommitted($budget->budget_id),2)}}
        </td>
        <td class="text-right">
            @if(ReportsController::totalNotSpent($budget->budget_id) > 0)
                {{number_format(ReportsController::totalNotSpent($budget->budget_id),2)}}
            @else
                {{number_format(0,2)}}
            @endif        
        </td>
        <td class="text-right">
            @if(ReportsController::totalSpent($budget->budget_id) > 0)
                {{number_format(ReportsController::totalSpent($budget->budget_id),2)}}
            @else
                {{number_format(0,2)}}
            @endif    
        </td>



        <!-- <td class="text-right">
            {{number_format(ReportsController::totalSpent($budget->budget_id),2)}}
        </td> -->
        <td class="text-right">
            {{number_format(ReportsController::calculateVAT($budget->budget_id),2)}}
        </td>



        
        <td class="text-right">
            {{number_format(ReportsController::totalGrossSpent($budget->budget_id) + ReportsController::calculateVATByItem($budget->budget_id,$budget->id),2)}}
        </td>
    </tr>
</tbody>

</table>
<div class="row float-right mt-3">
    <div class="col col-lg-12">
        <div>
            <div class="d-inline">
                Total Amount:
            </div>
            <div class="d-inline">
                {{number_format(ReportsController::totalBudgetById($budget->budget_id),2)}}
            </div>
            <div>
                @if(ReportsController::amountAvailable($budget->budget_id) < 0)
                <span>Available Amount: <span class="text-danger">Budget Over spent</span></span>
                @else
                    <div class="d-inline">
                        Available Amount:
                    </div>
                    <div class="d-inline">
                        {{number_format(ReportsController::amountAvailable($budget_id) - ReportsController::calculateVAT($budget_id),2)}}
                    </div>
                @endif
            </div>
                
        </div>
    </div>
</div>
</body>
</html>

