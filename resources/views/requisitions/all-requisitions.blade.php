<?php

use App\User;
use App\Comments\Comment;
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
use App\Http\Controllers\Requisitions\RequisitionsController;

$requisition = Requisition::where('req_no', $req_no)->get();

$req = Requisition::where('req_no', $req_no)->where('budget_id',0)->get();

$user = User::where('users.id', Requisition::where('req_no', $req_no)->distinct()->pluck('user_id'))
        ->join('departments','users.department_id','departments.id')
        ->select('users.*','departments.name as department')
        ->first();

$requisitions = Requisition::where('req_no', $req_no)
                          ->join('budgets','requisitions.budget_id','budgets.id')
                          ->join('items','requisitions.item_id','items.id')
                          ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                          ->get();

$comments = Comment::where('req_no', $req_no)->join('users','comments.user_id','users.id')->select('comments.*', 'users.username as username')->get();

$requisition = Requisition::where('req_no', $req_no)->first();

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
                                    <h4 class="card-title">Submitted Requisitions</h4>
                                    <p class="lead float-right" style="color: #35A45A;">
                                        {{ $requisition->req_no }}
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
                                                        <th>Requster Details</th>
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
                                                @if(!$requisitions->isEmpty())
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
                                                    @if($requisition->budget_id != 0)
                                                    <tr>
                                                       <td scope="col" class="text-center">{{$requisitions[0]->req_no}}</td>
                                                       <td scope="col" class="text-center">{{$requisitions[0]->budget}}</td>
                                                       @if($requisitions[0]->status == 'onprocess')
                                                        <td scope="col" class="text-center text-danger">{{$requisitions[0]->status}}</td>
                                                       @else
                                                       <td scope="col" style="color:#088958" class="text-center">{{$requisitions[0]->status}}</td>
                                                       @endif
                                                    </tr>
                                                    @endif
                                                </tbody>
                                                @endif
                                                @if($requisitions->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>Requisition Details</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Requisition No.</th>
                                                        <th scope="col" class="text-center">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                        @if($req[0]->budget_id == 0)
                                                            <tr>
                                                           <td scope="col" class="text-center">{{$req[0]->req_no}}</td>
                                                           @if($req[0]->status == 'onprocess')
                                                            <td scope="col" class="text-center text-danger">{{$req[0]->status}}</td>
                                                           @endif
                                                           <td scope="col" style="color:#088958" class="text-center">{{$req[0]->status}}</td>

                                                        </tr>
                                                        @endif
                                                </tbody>
                                                @endif
                                            </table>
                                        </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-12 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                @if(!$requisitions->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>Totals Summary</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Serial No.</th>
                                                        <th scope="col" class="text-center">Budget Line</th>
                                                        <th scope="col" class="text-center">Item Name</th>
                                                        <th scope="col" class="text-center">Desciption</th>
                                                        <th scope="col" class="text-center">Requisition Date</th>
                                                        <th scope="col" class="text-center">Unit of Measure</th>
                                                        <th scope="col" class="text-center">Quantity</th>
                                                        <th scope="col" class="text-center">Unit Price</th>
                                                        <th scope="col" class="text-center">VAT Amount</th>
                                                        <th scope="col" class="text-center">Gross Amount</th>
                                                        <th scope="col" class="text-center">Amount Paid</th>
                                                        <th scope="col" class="text-center">Amount Remained</th>
                                                        @if($requisitions[0]->status = 'Retired')
                                                        @else
                                                        <th scope="col" class="text-center">Action</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($requisitions as $requisition)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$requisition->serial_no}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->item}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->item_name}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->description}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->created_at->toFormattedDateString()}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->unit_measure}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->quantity}}</td>
                                                           <td scope="col" class="text-center">{{number_format($requisition->unit_price,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($requisition->vat_amount,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($requisition->gross_amount,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format(RequisitionsController::getAmountPaid($req_no,$requisition->serial_no),2)}}</td>

                                                           <td scope="col" class="text-center">{{number_format(RequisitionsController::getTotalPerSerialNo($req_no,$requisition->serial_no) - RequisitionsController::getAmountPaid($req_no,$requisition->serial_no),2)}}</td>

                                                           @if($requisition->status == 'Retired')


                                                              <!-- <td scope="col" class="text-center"></td> -->


                                                           @elseif($requisition->user_id == Auth::user()->id && $requisition->status != 'Confirmed')
                                                              <td scope="col" class="text-center">
                                                                <a href="{{url('edit-requisition/'.$requisition->id)}}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                                <a href="{{url('delete-requisition/'.$requisition->req_no.'/'.$requisition->id)}}">
                                                                  <span>
                                                                     <i style="cursor: pointer;" class="material-icons submit-requisition md-10 align-middle mb-1 text-danger">delete_forever</i>
                                                                  </span>
                                                                </a>
                                                              </td>
                                                           @else

                                                             <td scope="col" class="text-center">
                                                              <span class="badge badge-sm badge-danger">Cannot Edit</span>

                                                             </td>

                                                           @endif
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
                                                        <td scope="col" class="text-center">{{number_format(RequisitionsController::getRequisitionTotal($requisition->req_no),2)}}</td>
                                                        <td scope="col" class="text-center">{{number_format(RequisitionsController::getTotalAmountPaid($req_no),2)}}</td>
                                                        <td></td>
                                                        <td style="width: 200px;" scope="col" class="text-center">
                                                            @if($requisition->user_id != Auth::user()->id)

                                                                @if($requisition->status == 'Confirmed')
                                                                <a id="approveBtn" href="{{url('process-payment/'.$requisition->req_no)}}" class="btn btn-sm btn-info">
                                                                 Process Payment
                                                                </a>
                                                                <a href="{{url('reject-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-warning">Reject</a>

                                                                @endif

                                                                @if($requisition->status == 'onprocess')
                                                                <a id="approveBtn" href="{{url('approve-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-info">
                                                                 Approve
                                                                </a>
                                                                <a href="{{url('reject-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-warning">Reject</a>
                                                                @endif

                                                                @if($requisition->gross_amount == RequisitionsController::getTotalAmountPaid($requisition->req_no))
                                                                        <p class="text-center">Can't Pay More</p>

                                                                    @endif

                                                                @if($requisition->status == 'Paid')

                                                                    <a id="approveBtn" href="{{url('approve-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-info">
                                                                     Process Payment
                                                                    </a>
                                                                @else



                                                                @if($requisition->status != 'onprocess' && $requisition->status != 'Confirmed')
                                                                <a id="approveBtn" href="{{url('approve-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-info">
                                                                 Approve
                                                                </a>
                                                                <a href="{{url('reject-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-warning">Reject</a>
                                                                @endif
                                                            <!-- <p>No Action</p> -->
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                @endif
                                                @if($requisitions->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>Totals Summary</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Serial No.</th>
                                                        <th scope="col" class="text-center">Item Name</th>
                                                        <th scope="col" class="text-center">Desciption</th>
                                                        <th scope="col" class="text-center">Unit of Measure</th>
                                                        <th scope="col" class="text-center">Quantity</th>
                                                        <th scope="col" class="text-center">Unit Price</th>
                                                        <th scope="col" class="text-center">VAT Amount</th>
                                                        <th scope="col" class="text-center">Gross Amount</th>
                                                        <th scope="col" class="text-center">Amount Paid</th>
                                                        <th scope="col" class="text-center">Amount Remained</th>
                                                        <th scope="col" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $req = Requisition::where('req_no', $req_no)->where('budget_id',0)->get(); ?>
                                                    @foreach($req as $req)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$req->serial_no}}</td>
                                                           <td scope="col" class="text-center">{{$req->item_name}}</td>
                                                           <td scope="col" class="text-center">{{$req->description}}</td>
                                                           <td scope="col" class="text-center">{{$req->unit_measure}}</td>
                                                           <td scope="col" class="text-center">{{$req->quantity}}</td>
                                                           <td scope="col" class="text-center">{{number_format($req->unit_price,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($req->vat_amount,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format($req->gross_amount,2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format(RequisitionsController::getAmountPaid($req->req_no,$req->serial_no),2)}}</td>
                                                           <td scope="col" class="text-center">{{number_format(RequisitionsController::getTotalPerSerialNo($req->req_no,$req->serial_no) - RequisitionsController::getAmountPaid($req->req_no,$req->serial_no),2)}}</td>
                                                           <td scope="col" class="text-center"><a href="{{url('edit-requisition/'.$requisition->id)}}" class="btn btn-sm btn-outline-info">Edit</a></td>
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
                                                        <td scope="col" class="text-center">{{number_format(RequisitionsController::getRequisitionTotal($requisition->req_no),2)}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="width: 150px;" scope="col" class="text-center">

                                                            @if($requisition->status == 'Paid')

                                                                    <a id="approveBtn" href="{{url('approve-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-info">
                                                                     Process Payment
                                                                    </a>


                                                            @elseif($requisition->user_id != Auth::user()->id)

                                                                <a id="approveBtn" href="{{url('approve-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-info">Approve</a>
                                                                <a href="{{url('reject-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-warning">Reject</a>
                                                            <!-- <p>No Action</p> -->
                                                            @endif


                                                        </td>
                                                    </tr>
                                                </tbody>
                                                @endif
                                            </table>
                                        </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-6 mt-2">
                                        <form action="{{ route('comment.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="req_no" value="{{$req_no}}">
                                            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <small class="text-primary">Add Comments</small>
                                                        <textarea placeholder="Add Comments" style="resize: none;" rows="2" class="form-control" name="body" data-toogle="tooltip" data-placement="top" title="Add Some Comments">

                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Add Comment</button>
                                        </form>
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

    $(document).ready(function() {
        $('.preload').fadeOut('3000', function() {
            $('.mydata').fadeIn('2000');
        });
    });

    $("#approveBtn").attr("href","Process Payment");

    //$("#approveBtn").html('Process Payment');

</script>
