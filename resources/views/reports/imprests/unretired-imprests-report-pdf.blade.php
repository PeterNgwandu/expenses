<?php
use Illuminate\Support\Carbon;
use App\Http\Controllers\Reports\ReportsController;

?>
<!DOCTYPE html>
<html>
<head>
	<title>Unretired Imprests Report</title>
	<!-- App CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<p class="lead font-weight-bold">Unretired Imprests Report  
    <span class="float-right">{{Carbon::now()->toFormattedDateString()}}</span> 
</p>
<table id="data-table" style="width: 100%" class="table table-sm table-bordered mb-0">
<thead>
    <tr>
        <th scope="col" scope="col" style="width: 80px;"class="text-center">Req No.</th>
        <th scope="col" scope="col" style="width: 140px;" class="text-center">Requester</th>
        <th scope="col" style="width: 140px;" class="text-center">Department</th>
        <th scope="col" style="width: 170px;" class="text-center">Activity Name</th>
        <th scope="col" class="text-center">Date Requested</th>
        <th scope="col" style="width: 100px;" class="text-center">Paid Date</th>
        <th scope="col" class="text-center">Total Requested</th>
        <th scope="col" class="text-center">Amount Paid</th>
    </tr>
</thead>
<tbody>
   @foreach($unretired_imprest as $imprest)
    <tr>
        <td class="text-left">{{ $imprest->req_no }}</td>
        <td class="text-left">{{ $imprest->requester }}</td>
        <td class="text-left">{{ $imprest->department }}</td>
        <td class="text-left">{{ $imprest->activity_name }}</td>
        <td class="text-left">{{ date('Y-m-d', strtotime($imprest->created_at)) }}</td>
        <td class="text-left">{{ $imprest->payment_date }}</td>

        <td class="text-right">{{ number_format(ReportsController::calculateAmountRequested($imprest->req_no),2) }}</td>

        <td class="text-right">{{ number_format(ReportsController::calculateAmountPaid($imprest->req_no),2) }}</td>
    </tr>
   @endforeach
    
</tbody>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td class="text-right font-weight-bold">Total Unretired</td>
    <td class="text-right">{{ number_format(ReportsController::calculateTotalUnretiredImrestsCostsBasedOnFilter($from, $to),2) }}</td>
</tr>

</table>
</body>
</html>

