<?php
use App\User;
use App\Comments\Comment;
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
use App\Http\Controllers\Requisitions\RequisitionsController;

$requisition = Requisition::where('req_no', $req_no)->where('requisitions.status', '!=', 'Deleted')->where('requisitions.status', '!=', 'Edited')->get();

$req = Requisition::where('req_no', $req_no)->where('requisitions.status', '!=', 'Deleted')->where('requisitions.status', '!=', 'Edited')->where('budget_id',0)->get();
$vat_amount_no_budget = Requisition::where('req_no', $req_no)->where('requisitions.status', '!=', 'Deleted')->where('requisitions.status', '!=', 'Edited')->where('budget_id',0)->sum('vat_amount');

$user = User::where('users.id', Requisition::where('req_no', $req_no)->distinct()->pluck('user_id'))
        ->join('departments','users.department_id','departments.id')
        ->select('users.*','departments.name as department')
        ->first();

$requisitions = Requisition::where('req_no', $req_no)
                          ->join('budgets','requisitions.budget_id','budgets.id')
                          ->join('items','requisitions.item_id','items.id')
                          ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                          ->where('requisitions.status', '!=', 'Deleted')
                          ->where('requisitions.status', '!=', 'Edited')
                          ->get();

$comments = Comment::where('req_no', $req_no)->join('users','comments.user_id','users.id')->select('comments.*', 'users.username as username')->get();

$requisition = Requisition::where('req_no', $req_no)->where('requisitions.status', '!=', 'Deleted')->where('requisitions.status', '!=', 'Edited')->first();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Requisition Report</title>
	<!-- App CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="{{url('assets/bootstrap/scss/bootstrap.scss')}}"> --}}
</head>
<body>
    <div class="float-right">
        <img style="height:90px;width:100" src="{{public_path('assets/images/fastpay.jpg')}}" alt="FastPay Solutions Logo">
        <h5 class="font-weight-bold">FastPay Solutions Limited</h5>
        <p>{{Carbon::now()->toFormattedDateString()}}</p>
    </div><br><br><br>
        <div class="row" style="margin-left: -60px !important;">
            <div class="col-8">
                <div class="row">
                    <div class="col-lg-8">
                        <ul style="list-style-type: none">
                            <h5 class="font-weight-bold">Requster Details</h5>
                            <li>Name : {{$user->username}}</li>
                            <li>Email : {{$user->email}}</li>
                            <li>Phone : {{$user->phone}}</li>
                            <li>Department : {{$user->department}}</li>
                        </ul>

                        @if(!$requisitions->isEmpty())
                        <ul style="list-style-type: none">
                            <h5 class="font-weight-bold">Requisition Details</h5>
                            <li>Requsition # : {{$requisitions[0]->req_no}}</li>
                            <li>Activity Name : {{$requisitions[0]->activity_name}}</li>
                            <li>Budget : {{$requisitions[0]->budget}}</li>
                            <li>Status : {{$requisitions[0]->status}}</li>
                        </ul>
                        @elseif($requisitions->isEmpty())
                            <ul style="list-style-type: none">
                                <h5 class="font-weight-bold">Requisition Details</h5>
                                <li>Requsition # : {{$req[0]->req_no}}</li>
                                <li>Activity Name : {{$req[0]->activity_name}}</li>
                                <li>Status : {{$req[0]->status}}</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
                <table class="table table-sm table-bordered">
                    @if(!$requisitions->isEmpty())
                        <thead>

                            <tr>
                                <th>S/N.</th>
                                <th>Budget Line</th>
                                <th>Item</th>
                                <th>Desciption</th>
                                <th>Date</th>
                                <th>UOM</th>
                                <th>QTY</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submitted_requisitions as $requisition)
                                <tr>
                                    <td>{{$requisition->serial_no}}</td>
                                    <td>{{$requisition->item}}</td>
                                    <td>{{$requisition->item_name}}</td>
                                    <td>{{$requisition->description}}</td>
                                    <td>{{$requisition->created_at->toFormattedDateString()}}</td>
                                    <td>{{$requisition->unit_measure}}</td>
                                    <td>{{$requisition->quantity}}</td>
                                    <td class="text-right">{{number_format($requisition->unit_price,2)}}</td>
                                    {{-- <td >{{number_format($requisition->vat_amount,2)}}</td> --}}
                                    <td class="text-right">{{number_format($requisition->gross_amount,2)}}</td>

                                </tr>

                            @endforeach

                        </tbody>
                        @endif
                        @if($requisitions->isEmpty())
                        <thead>
                            <tr>
                                <th>S/N.</th>
                                <th>Item</th>
                                <th>Desciption</th>
                                <th>UOM</th>
                                <th>QTY</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $req = Requisition::where('req_no', $req_no)->where('requisitions.status','!=','Deleted')->where('requisitions.status', '!=', 'Edited')->where('budget_id',0)->get(); ?>
                            @foreach($req as $req)
                                <tr>
                                    <td>{{$req->serial_no}}</td>
                                    <td>{{$req->item_name}}</td>
                                    <td>{{$req->description}}</td>
                                    <td>{{$req->unit_measure}}</td>
                                    <td>{{$req->quantity}}</td>
                                    <td class="text-right">{{number_format($req->unit_price,2)}}</td>
                                    <td class="text-right">{{number_format($req->gross_amount,2)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>

        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="float-right">
                    <p class="font-weight-bold">Total Excl. VAT : {{number_format(RequisitionsController::getRequisitionTotal($requisition->req_no) - $vat_amount,2)}}</p>
                    @if (!$requisitions->isEmpty())
                        <p>18% VAT : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{number_format($vat_amount,2)}}</p>
                    @elseif($requisitions->isEmpty())
                        <p>18% VAT : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{number_format($vat_amount_no_budget,2)}}</p>
                    @endif
                    <p class="font-weight-bold">Total Incl. VAT : &nbsp; {{number_format(RequisitionsController::getRequisitionTotal($requisition->req_no),2)}}</p>
                </div>
            </div>
        </div>
</body>
</html>
