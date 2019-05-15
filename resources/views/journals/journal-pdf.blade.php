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
<table class="table table-sm table-striped table-bordered">
                                            	
        <thead style="max-width: 30px;">
            <tr>
                <th scope="col" class="text-center">Date</th>
                <th scope="col" class="text-center">Req #</th>
                <th scope="col" class="text-center">User</th>
                {{-- <th scope="col" class="text-center">Activity Name</th> --}}
                <th scope="col" class="text-center">GL Accounts</th>
                <!-- <th scope="col" class="text-center">Currency</th> -->
                <th scope="col" class="text-center">DR</th>
                <th scope="col" class="text-center">CR</th>
                <!-- <th scope="col" class="text-center">Balance</th> -->
            </tr>
        </thead> 
        <tbody>
            
            @foreach($requisitions as $req)
                
                <tr>                                                   	
                    <td scope="col" class="text-center">{{$req->created_at}}</td>
                    <td scope="col" class="text-center">{{$req->req_no}}</td>
                    <td scope="col" class="text-center">{{$req->username}}</td>
                    
                    {{-- <td scope="col" class="text-center">{{$req->activity_name}}</td>                                                			 --}}
                    <td scope="col" class="text-center">{{$req->account_no}}</td>
                    <!-- <td scope="col" class="text-center">Tz</td> -->
                    <td scope="col" class="text-center">{{number_format($req->amount_paid)}}</td>
                    <td></td>
                    <!-- <td></td> -->
                </tr>
                <tr>
                    <td scope="col" class="text-center"></td>
                    <td scope="col" class="text-center"></td>
                    <td scope="col" class="text-center"></td>
                    
                    <td scope="col" class="text-center"></td>                                                            
                    <td scope="col" class="text-center">{{$req->account}}</td>
                    <!-- <td scope="col" class="text-center">Tz</td> -->
                    {{-- <td scope="col" class="text-center"></td> --}}
                    <td scope="col" class="text-center">{{number_format($req->amount_paid)}}</td>
                    <!-- <td scope="col" class="text-center">{{number_format($req->gross_amount + (-$req->gross_amount))}}</td> -->
                </tr>

        @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    {{-- <td></td> --}}
                    <td scope="col" class="text-center font-weight-bold">Total</td>
                    <td scope="col" class="text-center font-weight-bold">{{number_format(JournalController::getTotalofPaidRequisitions())}}</td>
                    <td scope="col" class="text-center font-weight-bold">{{number_format(JournalController::getTotalofPaidRequisitions())}}</td>
                </tr>
        </tbody>
    </table>


<p class="lead">Retirements</p>
<table class="table table-sm table-striped table-bordered">
        <thead style="max-width: 30px;">
            <tr>
                <th scope="col" class="text-center">Date</th>
                <th scope="col" class="text-center">Ret #.</th>
                <th scope="col" class="text-center">Req #.</th>
                <th scope="col" class="text-center">Item Name</th>
                <th scope="col" class="text-center">Supplier</th>
                <th scope="col" class="text-center">GL Accounts</th>
                <th scope="col" class="text-center">VAT</th>
                <th scope="col" class="text-center">DR</th>
                <th scope="col" class="text-center">CR</th>
            </tr>
        </thead> 
        <tbody>
            @foreach($retirements as $ret)
                <tr>

                    <td scope="col" class="text-center">{{$ret->created_at}}</td>
                    <td scope="col" class="text-center">{{$ret->ret_no}}</td>
                    <td scope="col" class="text-center">{{$ret->req_no}}</td>
                    <td scope="col" class="text-center">{{$ret->item_name}}</td>
                    <td scope="col" class="text-center">{{$ret->supplier_id}}</td>
                    <td scope="col" class="text-center">{{$ret->Account_No}}</td>
                    <td scope="col" class="text-center">{{$ret->vat}}</td>
                    <td scope="col" class="text-center">{{number_format($ret->total)}}</td>
                    <td></td>                                                		
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td scope="col" class="text-center">{{$ret->Account_Name}}</td>
                    <td></td>
                    <td scope="col" class="text-center"></td>
                    <td scope="col" class="text-center">{{number_format($ret->total)}}</td>
                   
                </tr>
        @endforeach
                
                
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td scope="col" class="text-center"></td>
                    <td scope="col" class="text-center font-weight-bold">Total</td>
                    <td scope="col" class="text-center font-weight-bold">{{number_format(JournalController::getTotalofRetiredRequisitions())}}</td>
                    <td scope="col" class="text-center font-weight-bold">{{number_format(JournalController::getTotalofRetiredRequisitions())}}</td> 
                </tr>
                
        </tbody>
    </table>
</body>
</html>

