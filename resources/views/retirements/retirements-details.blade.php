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

$retirement = Retirement::where('req_no', $req_no)
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
$amount_retired = Retirement::where('req_no', $req_no)->where('status', '!=', 'Edited')->sum('gross_amount');
$amount_requested = Requisition::where('requisitions.req_no', $requisition_no->req_no)->where('status','!=','Deleted')->where('status','!=','Edited')->sum('requisitions.gross_amount');
$amount_paid = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $req_no)->where('status', 'Pay')->sum('amount_paid');
$amount_received = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $requisition_no->req_no)->where('status', 'Receive')->sum('amount_paid');
$amount_returned = FinanceSupportiveDetail::where('finance_supportive_details.req_no', $requisition_no->req_no)->where('status', 'Return')->sum('amount_paid');
$amount_unretired = $amount_paid - ($amount_retired + $amount_received + $amount_returned);
$paid_amount = $amount_paid + $amount_returned;
$retired_amount = $amount_retired + $amount_received;
$amount_claimed = ($amount_retired + $amount_received) - $paid_amount;



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
                                    <h4 class="card-title">Retirement for Requisitions : {{$req_no}}</h4>
                                    <p class="lead float-right" style="color: #35A45A;">

                                    </p>
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
                                    <div class="col-lg-8 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Retirement Details</th>
                                                    </tr>
                                                    <tr>
                                                        <th  scope="col" class="text-center">Requisition No.</th>
                                                        <!-- <th  scope="col" class="text-center">Status</th> -->
                                                        <th  scope="col" class="text-center">Full/Partially Retired</th>
                                                        <th  scope="col" class="text-center">Amount Paid</th>
                                                        <th  scope="col" class="text-center">Amount Unretired</th>
                                                        <th  scope="col" class="text-center">Amount To Claim</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                       <td scope="col" class="text-center">{{$requisition_no->req_no}}</td>
                                                       <!-- <td scope="col" class="text-center">{{$requisition_no->status}}</td> -->
                                                       <td scope="col" class="text-center">Partially Retired</td>
                                                       <td scope="col" class="text-right">{{number_format($paid_amount,2)}}</td>
                                                       <td scope="col" class="text-center">
                                                           @if($paid_amount <= $retired_amount)
                                                              N/A
                                                           @elseif($paid_amount > $retired_amount)
                                                              {{number_format($amount_unretired,2)}}
                                                           @endif

                                                       </td>
                                                       <td scope="col" class="text-center">
                                                           @if($paid_amount < $retired_amount)
                                                              {{number_format($amount_claimed,2)}}
                                                           @elseif($paid_amount >= $retired_amount)
                                                              N/A
                                                           @endif

                                                       </td>

                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-8 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Retirements</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Retirement No.</th>
                                                        <th scope="col" class="text-center">Retired Amount</th>
                                                        <!-- <th scope="col" class="text-center">Paid Amount</th> -->
                                                        <th scope="col" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($retirements as $retirement)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$retirement->ret_no}}</td>
                                                           <td scope="col" class="text-right">{{number_format(RetirementController::amountRetired($retirement->ret_no),2)}}</td>
                                                           <!-- <td scope="col" class="text-right">{{number_format($amount_paid,2)}}</td> -->
                                                           <td scope="col" class="text-center">
                                                              <a href="{{route('view-retirements',$retirement->ret_no)}}" class="btn btn-sm btn-twitter">View Retirement</a>
                                                           </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>

                                                        <td scope="col" class="text-center font-weight-bold">Total</td>
                                                        <td scope="col" class="text-right">{{number_format(RetirementController::getTotalOfRetiredLines($retirement->req_no),2)}}</td>
                                                        <!-- <td scope="col" class="text-right">{{number_format($amount_paid,2)}}</td> -->
                                                        <td scope="col" class="text-right"></td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="mb-2">
                                                @if($retired_amount < $paid_amount && Auth::user()->id == $retirement->user_id)
                                                  <a href="{{url('add-retirement/'.'RET-'.($getLatestRetNo + 1).'/'.$retirement->req_no)}}" class="btn btn-sm btn-twitter">Add Retirement</a>
                                                @endif
                                            </div>
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
