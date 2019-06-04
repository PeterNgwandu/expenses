<?php

use App\User;
use App\Limits\Limit;
use App\Comments\Comment;
use App\StaffLevel\StaffLevel;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use App\Comments\RetirementComment;
use App\Accounts\FinanceSupportiveDetail;
use App\Http\Controllers\Retirements\RetirementController;
use App\Http\Controllers\Requisitions\RequisitionsController;

$stafflevels = StaffLevel::all();

$hod = $stafflevels[0]->id;
$ceo = $stafflevels[1]->id;
$supervisor = $stafflevels[2]->id;
$normalStaff = $stafflevels[3]->id;
$financeDirector = $stafflevels[4]->id;

$limitSupervisor = Limit::where('stafflevel_id',$supervisor)
                        ->select('max_amount')->first();
$limitHOD = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();

$stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;
        $user = User::where('id', Auth::user()->id)->first();

$retirement = Retirement::where('ret_no', $ret_no)
                       ->first();

$user = User::where('users.id', $retirement->user_id)
        ->join('departments','users.department_id','departments.id')
        ->select('users.*','departments.name as department')
        ->first();

$submitted_requisitions = Requisition::where('requisitions.status', 'Paid')
                  ->join('budgets','requisitions.budget_id','budgets.id')
                  ->join('items','requisitions.item_id','items.id')
                  ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                  ->get();


$comments = RetirementComment::where('retirement_comments.ret_no', $retirement->ret_no)
            ->join('users','retirement_comments.user_id','users.id')
            ->join('retirements','retirement_comments.ret_no','retirements.ret_no')
            ->select('retirement_comments.*', 'users.username as username')
            ->distinct()
            ->get();

// Amount finance can approve after retirement has been approved by supervisor
$amount_finance_can_approve = Limit::where('stafflevel_id',$hod)->select('max_amount')->first();
// $amount_retired = Retirement::join('requisitions','retirements.req_no','requisitions.req_no')->where('retirements.req_no', $retirement->req_no)->where('retirements.status', '!=', 'Edited')->sum('retirements.gross_amount');
$amount_retired = Retirement::where('req_no', $requisition_no->req_no)->where('status', '!=', 'Edited')->sum('gross_amount');
$amount_requested = Requisition::where('requisitions.req_no', $requisition_no->req_no)->where('status','!=','Deleted')->where('status','!=','Edited')->sum('requisitions.gross_amount');
$amount_paid = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $requisition_no->req_no)->where('status', 'Pay')->sum('amount_paid');
$amount_received = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $requisition_no->req_no)->where('status', 'Receive')->sum('amount_paid');
$amount_returned = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $requisition_no->req_no)->where('status', 'Return')->sum('amount_paid');
$amount_unretired = $amount_paid - ($amount_retired + $amount_received + $amount_returned);
$paid_amount = $amount_paid + $amount_returned;
$retired_amount = $amount_retired + $amount_received;
$amount_claimed = ($amount_retired + $amount_received) - $paid_amount;

$unretired = $paid_amount - ($amount_retired + $amount_received);
?>
@extends('layout.app')
<style type="text/css">
    .mydata {
        display: none;
    }
    .preload {
        margin: 0px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        margin-top: 10px;
        background: #ffffff;
    }
    .img {
        background: #ffffff;
    }
</style>
@section('content')
<div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Retired Requisitions</h4>
                                    <p class="lead float-right" style="color: #35A45A;">

                                    </p>
                                    @if(Auth::user()->id == $retirement->user_id)
                                      @if($retirement_status->status == 'Retired' || $retirement_status->status == 'Retired, supervisor' || $retirement_status->status == 'Retired, hod' || $retirement_status->status == 'Retired, ceo' || $retirement_status->status == 'Retired, finance' || $retirement_status->status == 'Rejected By Supervisor' || $retirement_status->status == 'Rejected By HOD' || $retirement_status->status == 'Rejected By CEO' || $retirement_status->status == 'Consult Finance')
                                        <a href="{{url('edit-retirement-line/'. $ret_no)}}" retirement-number="{{$ret_no}}" style="border-radius: 0px !important;" class="btn enable-edit-retirement-line btn-sm btn-success mt-2">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons  md-2 align-middle">edit</i>
                                            </span>
                                            Edit
                                        </a>
                                      @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-6 ml-3">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Retiree Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Username : {{$user->username}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email : {{$user->email}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone : {{$user->phone}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Department : {{$user->department}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-10 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Retirement Details</th>
                                                    </tr>
                                                    <tr>
                                                        <th  scope="col" class="text-center">Requisition No.</th>
                                                        <th  scope="col" class="text-center">Status</th>
                                                        <th  scope="col" class="text-center">Paid Amount</th>
                                                        <th  scope="col" class="text-center">Total Amount Retired</th>
                                                        <th  scope="col" class="text-center">Amount Unretired</th>
                                                        <th  scope="col" class="text-center">Amount To Claim</th>
                                                        <th  scope="col" class="text-center">Full/Partial Retired</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                       <td scope="col" class="text-center">{{$requisition_no->req_no}}</td>
                                                       <td scope="col" class="text-center">{{$retirements[0]->status}}</td>
                                                       <td scope="col" class="text-right">{{number_format($paid_amount,2)}}</td>
                                                       <td scope="col" class="text-right">{{number_format($retired_amount,2)}}</td>
                                                       <td scope="col" class="text-center">
                                                          @if($unretired <= 0)
                                                            N/A
                                                          @else
                                                            {{number_format($unretired, 2)}}
                                                          @endif
                                                        </td>
                                                       <td scope="col" class="text-center">
                                                           @if($amount_claimed <= 0)
                                                             N/A
                                                           @else
                                                             {{number_format($amount_claimed, 2)}}
                                                           @endif
                                                       </td>
                                                       <td scope="col" class="text-center">
                                                           @if($paid_amount < $retired_amount || $paid_amount > $retired_amount)
                                                              Partially Retired
                                                           @elseif($paid_amount == $retired_amount)
                                                              Fully Retired
                                                           @endif
                                                       </td>

                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-12 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Totals Summary</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Item Purchased</th>
                                                        <th scope="col" class="text-center">Supplier</th>
                                                        <th scope="col" class="text-center">Reference No</th>
                                                        <th scope="col" class="text-center">Purchase Date</th>
                                                        <th scope="col" class="text-center">Desciption</th>
                                                        <th scope="col" class="text-center">Unit of Measure</th>
                                                        <th scope="col" class="text-center">Quantity</th>
                                                        <th scope="col" class="text-center">Unit Price</th>
                                                        <th scope="col" class="text-center">VAT Amount</th>
                                                        <th scope="col" class="text-center">Gross Amount</th>

                                                        <!-- <th scope="col" class="text-center">Offset</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($retirements as $retirement)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$retirement->item_name}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->supplier_id}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->ref_no}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->purchase_date}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->description}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->unit_measure}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->quantity}}</td>
                                                           <td scope="col" class="text-right">{{number_format($retirement->unit_price,2)}}</td>
                                                           <td scope="col" class="text-right">{{number_format($retirement->vat_amount,2)}}</td>
                                                           <td scope="col" class="text-right">{{number_format($retirement->gross_amount,2)}}</td>

                                                           <!-- <td scope="col" class="text-center">{{number_format(RetirementController::getRequestedAmount($retirement->req_no,$retirement->serial_no) - $retirement->gross_amount,2)}}</td> -->

                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td scope="col" class="text-center font-weight-bold">Total</td>
                                                        <td scope="col" class="text-right">{{number_format(RetirementController::getRetirementTotal($retirement->ret_no),2)}}</td>
                                                        <!-- <td scope="col" class="text-right">{{number_format($amount_paid,2)}}</td> -->

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-6 mt-2">
                                        <small class="text-primary">Add Comments</small>
                                        <form method="POST" action="{{route('retirements.comments')}}">
                                            @csrf
                                            <input type="hidden" name="ret_no" value="{{$ret_no}}">
                                            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <textarea style="resize: none;" rows="2" class="form-control" name="body" placeholder="Add Comments" data-toogle="tooltip" data-placement="top" title="Select Add Some Comments">

                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Comment</button>&nbsp;&nbsp;&nbsp;
                                                <td style="width: 129px;" scope="col" class="text-center">
                                                    <!-- @if($amount_retired < $amount_paid && Auth::user()->id == $retirement->user_id)
                                                      <a href="{{url('add-retirement/'.RetirementController::getTheLatestRetirementNumber().'/'.$retirement->req_no)}}" class="btn btn-sm btn-twitter">Add Retirement</a>
                                                    @endif -->
                                                    @if($paid_amount > $retired_amount && $retirement->user_id == Auth::user()->id)
                                                      <a href="{{url('add-retirement/'.'RET-'.($getLatestRetNo + 1).'/'.$retirement->req_no)}}" class="btn btn-sm btn-twitter">Add Retirement</a>&nbsp;
                                                    @endif
                                                    @if ($retirement->status == 'Confirmed')
                                                        <span class="badge badge-twitter text-twitter">Retirement Confirmed</span>
                                                    @endif
                                                    @if(Auth::user()->stafflevel_id != $normalStaff)
                                                        @if($retirement->user_id != Auth::user()->id && $retirement->status == 'Retired' && Auth::user()->stafflevel_id == $supervisor)

                                                            <a href="{{url('approve-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-info">Approve</a>
                                                            <a href="{{url('reject-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-warning">Reject</a>

                                                        @elseif($retirement->status == 'Approved By Supervisor' || $retirement->status == 'Retired, supervisor' && Auth::user()->stafflevel_id == $hod)
                                                            <a href="{{url('approve-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-info">Approve</a>
                                                            <a href="{{url('reject-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-warning">Reject</a>

                                                        @elseif($retirement->user_id != Auth::user()->id && $retirement->status == 'Approved By Finance' || $retirement->status == 'Retired, finance' && Auth::user()->stafflevel_id == $ceo)
                                                            <a href="{{url('approve-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-info">Approve</a>
                                                            <a href="{{url('reject-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-warning">Reject</a>
                                                        @elseif($retirement->status == 'Approved By HOD' || $retirement->status == 'Retired, hod' && Auth::user()->stafflevel_id == $financeDirector && $retirement->user_id != Auth::user()->id && $retirement->gross_amount > $amount_finance_can_approve->max_amount)
                                                            <a href="{{url('approve-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-info">Approve</a>
                                                            <a href="{{url('reject-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-warning">Reject</a>
                                                        @elseif($retirement->status == 'Approved By Supervisor' || $retirement->status == 'Retired, hod' || $retirement->status == 'Retired, ceo' && Auth::user()->stafflevel_id == $financeDirector && $retirement->user_id != Auth::user()->id && $retirement->gross_amount < $amount_finance_can_approve->max_amount)
                                                            <a href="{{url('approve-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-info">Approve</a>
                                                            <a href="{{url('reject-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-warning">Reject</a>

                                                        @endif
                                                      @endif

                                                      @if(Auth::user()->stafflevel_id == $financeDirector && $retirement->user_id != Auth::user()->id)
                                                          @if($paid_amount > $retired_amount && $retirement->status == 'Confirmed')
                                                            <a href="{{url('receive-receipt/'.$retirement->req_no)}}" class="btn btn-sm btn-twitter">Receive Receipt</a>
                                                          @elseif($paid_amount < $retired_amount && $retirement->status == 'Confirmed')
                                                            <a href="{{url('return-balance/'.$retirement->req_no)}}" class="btn btn-sm btn-facebook">Pay Balance</a>
                                                          @endif
                                                      @endif
                                                </td>
                                        </form>
                                        @if($flash = session('messages'))
                                            <div id="flash" class="alert alert-info">
                                                {{ $flash }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-12 mt-2">
                                        <h5 class="text-danger">Comments</h5>
                                        <ul class="list-group list-group-flush">
                                            @if(!$comments->isEmpty())
                                               @foreach($comments as $comment)
                                                <li class="list-group-item mb-1 mr-4">{{$comment->body}}
                                                    <span class="float-right badge badge-sm badge-primary">
                                                        {{$comment->username}}
                                                    </span>
                                                </li>
                                               @endforeach
                                            @else
                                                <p>No Comments</p>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script type="text/javascript">
    $(function() {
       $('#flash').delay(500).fadeIn('normal', function() {
          $(this).delay(2500).fadeOut();
       });
    });
    $(document).ready(function() {
        $('.preload').fadeOut('3000', function() {
            $('.mydata').fadeIn('2000');
        });
    });

</script>
