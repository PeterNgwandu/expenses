<?php

use App\User;
use App\Comments\Comment;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use App\Http\Controllers\Retirements\RetirementController;
use App\Http\Controllers\Requisitions\RequisitionsController;



$user = User::where('users.id', Requisition::where('req_no', $req_no)->distinct()->pluck('user_id'))
        ->join('departments','users.department_id','departments.id')
        ->select('users.*','departments.name as department')
        ->first();

$comments = Comment::where('req_no', $req_no)->join('users','comments.user_id','users.id')->select('comments.*', 'users.username as username')->get();



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
                                    <h4 class="card-title">Paid Requisitions</h4>
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
                                                        <th>Requester Details</th>
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
                                    <div class="float-right mr-4 mt-4">

                                    </div>
                                    <div class="col-lg-6 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                @if(!$submitted_requisitions->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>Requisition Details</th>
                                                    </tr>
                                                    <tr>
                                                        <th  scope="col" class="text-center">Requisition No.</th>
                                                        <th scope="col" class="text-center">Budget</th>
                                                        <th scope="col" class="text-center">Status</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$submitted_requisitions[0]->req_no}}</td>
                                                           <td scope="col" class="text-center">{{$submitted_requisitions[0]->budget}}</td>
                                                           <td scope="col" class="text-center">{{$submitted_requisitions[0]->status}}</td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                            @endif
                                            @if($submitted_requisitions->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>Requisition Details</th>
                                                    </tr>
                                                    <tr>
                                                        <th  scope="col" class="text-center">Requisition No.</th>
                                                        <th  scope="col" class="text-center">Status</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($submitted_paid_no_budget as $retirement)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$retirement->req_no}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->status}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @endif
                                        </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-12 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                @if(!$submitted_requisitions->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>Totals Summary</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Budget Line</th>
                                                        <th scope="col" class="text-center">Item Purchased</th>
                                                        <th scope="col" class="text-center">Desciption</th>
                                                        <th scope="col" class="text-center">Unit of Measure</th>
                                                        <th scope="col" class="text-center">Quantity</th>
                                                        <th scope="col" class="text-center">Unit Price</th>
                                                        <th scope="col" class="text-center">VAT Amount</th>
                                                        <th scope="col" class="text-center">Gross Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($submitted_requisitions as $retirement)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$retirement->item}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->item_name}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->description}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->unit_measure}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->quantity}}</td>
                                                           <td scope="col" class="text-center">{{number_format($retirement->unit_price,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($retirement->vat_amount,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($retirement->gross_amount,2)}}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td scope="col" class="text-center">Total</td>
                                                        <td scope="col" class="text-center">{{number_format(RequisitionsController::getRequisitionTotal($retirement->req_no),2)}}</td>
                                                        <!-- <td scope="col" class="text-center">

                                                            @if($retirement->user_id != Auth::user()->id)
                                                                <a href="{{url('approve-retirement/'.$retirement->ret_no)}}" class="btn btn-sm btn-outline-info">Approve</a>

                                                                <a href="{{url('approve-retirement/'.$retirement->ret_no)}}" class="btn btn-sm btn-outline-warning">Reject</a>

                                                            @endif

                                                        </td> -->
                                                    </tr>
                                                </tbody>
                                                @endif
                                                @if($submitted_requisitions->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>Totals Summary</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Item Name</th>
                                                        <th scope="col" class="text-center">Desciption</th>
                                                        <th scope="col" class="text-center">Unit of Measure</th>
                                                        <th scope="col" class="text-center">Quantity</th>
                                                        <th scope="col" class="text-center">Unit Price</th>
                                                        <th scope="col" class="text-center">VAT Amount</th>
                                                        <th scope="col" class="text-center">Gross Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($submitted_paid_no_budget as $retirement)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$retirement->item_name}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->description}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->unit_measure}}</td>
                                                           <td scope="col" class="text-center">{{$retirement->quantity}}</td>
                                                           <td scope="col" class="text-center">{{number_format($retirement->unit_price,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($retirement->vat_amount,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($retirement->gross_amount,2)}}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td scope="col" class="text-center">Total</td>
                                                        <td scope="col" class="text-center">{{number_format(RequisitionsController::getRequisitionTotal($retirement->req_no),2)}}</td>
                                                        <!-- <td scope="col" class="text-center">
                                                            @if($retirement->user_id != Auth::user()->id)
                                                                <a href="{{url('approve-retirement/'.$retirement->ret_no)}}" class="btn btn-sm btn-outline-info">Approve</a>
                                                                @if($retirement->gross_amount > 100000)
                                                                <a href="{{url('approve-retirement/'.$retirement->ret_no)}}" class="btn btn-sm btn-outline-warning">Reject</a>
                                                            @endif
                                                            @endif
                                                            @if($retirement->user_id == Auth::user()->id)
                                                                <span class="badge badge-danger">
                                                                    No Action
                                                                </span>
                                                                {{-- @if($retirement->status !== 'Retired')
                                                                <a href="{{route('retire',$retirement->req_no)}}" class="btn btn-sm btn-outline-primary">Retire</a>
                                                                @else
                                                                <a href="{{route('retire',$retirement->req_no)}}" class="btn btn-sm btn-outline-primary">Retire</a>
                                                                @endif --}}
                                                            @endif
                                                        </td> -->
                                                    </tr>
                                                </tbody>
                                                @endif
                                            </table>
                                            <a href="{{route('retire',$req_no)}}" class="btn btn-twitter float-left">Retire</a>
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

    </div>
</div>

@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('.preload').fadeOut('3000', function() {
            $('.mydata').fadeIn('2000');
        });
    });

    $(function() {
       $('#flash').delay(500).fadeIn('normal', function() {
          $(this).delay(2500).fadeOut();
       });
    });

</script>
