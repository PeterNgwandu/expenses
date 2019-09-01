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
<p class="lead">Imprest Given (Advance)</p>
<table class="table table-sm table-striped table-bordered mt-4">
                                                
    <thead style="max-width: 30px;">
        <tr>
            <th scope="col" class="text-center">Date</th>
            <th scope="col" class="text-center">Req #</th>
            <th scope="col" class="text-center">User</th>
            <th scope="col" class="text-center">Activity Name</th>
            <th scope="col" class="text-center">GL Accounts</th>
            <th scope="col" class="text-center">DR</th>
            <th scope="col" class="text-center">CR</th>
        </tr>
    </thead> 
    <tbody>
            @foreach($journal as $req)
        
        <tr>                                                    
            <td scope="col" class="text-center">{{$req->created_at}}</td>
            <td scope="col" class="text-center">{{$req->req_no}}</td>
            <td scope="col" class="text-left">{{$req->username}}</td>
            
            <td scope="col" class="text-left">{{$req->activity_name}}</td>                                                          
            <td scope="col" class="text-left">{{$req->account_no}}</td>
            <td scope="col" class="text-right">{{number_format($req->amount_paid)}}</td>
            <td></td>
        </tr>
        <tr>
            <td scope="col" class="text-center">{{$req->created_at}}</td>
            <td scope="col" class="text-center">{{$req->req_no}}</td>
            <td scope="col" class="text-left">{{$req->username}}</td>
            
            <td scope="col" class="text-left">{{$req->activity_name}}</td>                                                            
            <td scope="col" class="text-left">{{$req->account}}</td>
            <td scope="col" class="text-center"></td>
            <td scope="col" class="text-right">{{number_format($req->amount_paid)}}</td>
        </tr>

@endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td scope="col" class="text-right font-weight-bold">Total</td>
            @if(!$journal->isEmpty())
                <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofJournalRequisition($journal_no),2)}}</td>
                <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofJournalRequisition($journal_no),2)}}</td>
            @else
                <td scope="col" class="text-center"></td>
                <td scope="col" class="text-center"></td>
            @endif
        </tr>                                                    
    </tbody>
</table>
</body>
</html>

