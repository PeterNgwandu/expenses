<?php
use App\Http\Controllers\Reports\ReportsController;
use App\Http\Controllers\Journal\JournalController;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Refunds Received Report</title>
	<!-- App CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<p class="lead">Refunds Received Report </p>
<table class="table table-sm table-striped table-bordered mt-4">
                                                
    <thead style="max-width: 30px;">
        <tr>
            <th scope="col" class="text-center">Req No.</th>
            <th scope="col" class="text-center">Requester</th>
            <th scope="col" class="text-center">Department</th>
            <th scope="col" class="text-center">Activity Name</th>
            <th scope="col" class="text-center">Date Requested</th>
            <th scope="col" class="text-center">Received Date</th>
            <th scope="col" class="text-center">Amount Received</th>
        </tr>
    </thead> 
    <tbody>
          @foreach($balance_received as $balance)
            <tr>
                <td scope="col" class="text-left">{{ $balance->req_no }}</td>
                <td scope="col" class="text-left">{{ $balance->username }}</td>
                <td scope="col" class="text-left">{{ $balance->department }}</td>
                <td scope="col" class="text-left">{{ $balance->activity_name }}</td>
                <td scope="col" class="text-left">{{ date('Y-m-d',strtotime($balance->created_at)) }}</td>
                <td scope="col" class="text-left">{{ $balance->payment_date }}</td>
                <td scope="col" class="text-right">{{ number_format($balance->amount_paid,2) }}</td>
            </tr>
          @endforeach
            
    </tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td scope="col" class="text-info">Total Balance Received</td>
            <td scope="col" class="text-right">
              {{number_format(ReportsController::refundsReceivedTotalBasedOnPaymentDate($from, $to),2)}}
            </td>
        </tr>
    </table>
</table>
</body>
</html>

