<?php

use App\User;
use App\Comments\Comment;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use App\Comments\RetirementComment;
use App\Http\Controllers\Retirements\RetirementController;
use App\Http\Controllers\Requisitions\RequisitionsController;

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
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-4 ml-3">
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
                                    <div class="col-lg-6 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Retirement Details</th>
                                                    </tr>
                                                    <tr>
                                                        <th  scope="col" class="text-center">Requisition No.</th>
                                                        <th  scope="col" class="text-center">Status</th>
                                                        <th  scope="col" class="text-center">Amount To Return</th>
                                                        <th  scope="col" class="text-center">Amount To Claim</th>
                                                        <!-- <th scope="col" class="text-center">Budget</th>
                                                        <th scope="col" class="text-center">Budget Line</th> -->

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                       <td scope="col" class="text-center">{{$retirements[0]->req_no}}</td>
                                                       <td scope="col" class="text-center">{{$retirements[0]->status}}</td>

                                                       @if((RetirementController::getTotalRequestedAmount($retirement->req_no)) > (RetirementController::getRetirementTotal($retirement->ret_no)))

                                                        <td><p>Nothing to return</p></td>

                                                       @else

                                                       <td scope="col" class="text-center">{{number_format(RetirementController::getTotalRequestedAmount($retirement->req_no) - RetirementController::getRetirementTotal($retirement->ret_no),2)}}</td>

                                                       @endif

                                                       <td scope="col" class="text-center">{{number_format(RetirementController::getRetirementTotal($retirement->ret_no) - RetirementController::getTotalRequestedAmount($retirement->req_no),2)}}</td>
                                                       <!-- <td scope="col" class="text-center">{{$retirement->budget}}</td>
                                                       <td scope="col" class="text-center">{{$retirement->item}}</td> -->

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
                                                        <th scope="col" class="text-center">Retired Amount</th>
                                                        <th scope="col" class="text-center">Requested Amount</th>
                                                        <th scope="col" class="text-center">Offset</th>
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
                                                           <td scope="col" class="text-center">{{number_format($retirement->unit_price,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($retirement->vat_amount,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($retirement->gross_amount,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format(RetirementController::getRequestedAmount($retirement->req_no,$retirement->serial_no),2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format(RetirementController::getRequestedAmount($retirement->req_no,$retirement->serial_no) - $retirement->gross_amount,2)}}</td>

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
                                                        <td scope="col" class="text-center">Total</td>
                                                        <td scope="col" class="text-center">{{number_format(RetirementController::getRetirementTotal($retirement->ret_no),2)}}</td>
                                                        <td scope="col" class="text-center">{{number_format(RetirementController::getTotalRequestedAmount($retirement->req_no),2)}}</td>
                                                        <td style="width: 129px;" scope="col" class="text-center">
                                                            <!-- @if($retirement->approver_id == Auth::user()->id)
                                                              <a href="{{url('reject-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-warning">Reject</a>
                                                            @endif -->
                                                            @if($retirement->user_id != Auth::user()->id)

                                                                <a href="{{url('approve-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-info">Approve</a>

                                                                <a href="{{url('reject-retirement/'.$retirement->ret_no.'/'.Auth::user()->id)}}" class="btn btn-sm btn-outline-warning">Reject</a>

                                                            @endif

                                                        </td>
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
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Comment</button>
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
