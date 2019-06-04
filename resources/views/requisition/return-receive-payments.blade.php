<?php

use Illuminate\Support\Facades\Auth;
use App\Accounts\FinanceSupportiveDetail;
use App\Requisition\Requisition;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Requisitions\RequisitionsController;
use App\Http\Controllers\Accounts\FinanceSupportiveDetailsController;


// foreach ($data as $data) :

$requisitions = Requisition::where('req_no',$requisitionID->req_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->distinct()->get();

$requisitions2 = Requisition::where('requisitions.req_no', $requisitionID->req_no)
                          ->join('budgets','requisitions.budget_id','budgets.id')
                          ->join('items','requisitions.item_id','items.id')
                          ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                          ->where('requisitions.status', '!=', 'Deleted')
                          ->where('requisitions.status', '!=', 'Edited')
                          ->get();

?>

@extends('layout.app')

@section('content')

<style type="text/css">
    .requisition div {
        padding: 0px; margin-left: 0px; width: 150px;
    }
    .requisition div input {
        margin: 0px; padding: 0px; width: 100%
    }
    .requisition i:hover {
        color: #fff !important; background: purple
     }

    #flash {
        position: absolute;
        bottom: 10px;
        right: 20px;
        z-index: 10;
    }
</style>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h2 class="card-title">Receiving Receipts and Pay Balances Form</h2>
                                </div>
                            </div>
                        </div>
                        <h5 style="margin-top:20px;" class="lead text-primary ml-4">
                          <span>
                             <i style="cursor: pointer;" class="material-icons submit-requisition md-10 align-middle mb-1 text-primary">receipt</i>
                          </span>
                          Summary</h5>
                        <div class="col-lg-12">
                            <div class="col-lg-6 mt-2">
                                    <table class="table table-sm table-striped table-bordered">
                                        @if(!$requisitions2->isEmpty())
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
                                               <td scope="col" class="text-center">{{$requisitions2[0]->req_no}}</td>
                                               <td scope="col" class="text-center">{{$requisitions2[0]->budget}}</td>
                                               @if($requisitions2[0]->status == 'onprocess')
                                                <td scope="col" class="text-center text-danger">{{$requisitions2[0]->status}}</td>
                                               @else
                                               <td scope="col" style="color:#088958" class="text-center">{{$requisitions2[0]->status}}</td>
                                               @endif
                                            </tr>

                                        </tbody>
                                        @endif
                                        @if($requisitions2->isEmpty())
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
                                                <?php $req = Requisition::where('req_no', $req_no)->where('budget_id',0)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->get(); ?>
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
                        <div  class="col-lg-12">
                            <div class="col-lg-12 mt-2">
                                    <table class="table table-sm table-striped table-bordered">
                                        @if(!$requisitions2->isEmpty())
                                        <thead>
                                            <tr>
                                                <th>Totals Summary</th>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="text-center">Budget Line</th>
                                                <th scope="col" class="text-center">Item Name</th>
                                                <th scope="col" class="text-center">Desciption</th>
                                                <th scope="col" class="text-center">Requisition Date</th>
                                                <th scope="col" class="text-center">Unit of Measure</th>
                                                <th scope="col" class="text-center">Quantity</th>
                                                <th scope="col" class="text-center">Unit Price</th>
                                                <th scope="col" class="text-center">VAT Amount</th>
                                                <th scope="col" class="text-center">Gross Amount</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($requisitions2 as $requisition)
                                                <tr>
                                                   <td scope="col" class="text-center">{{$requisition->item}}</td>
                                                   <td scope="col" class="text-center">{{$requisition->item_name}}</td>
                                                   <td scope="col" class="text-center">{{$requisition->description}}</td>
                                                   <td scope="col" class="text-center">{{$requisition->created_at->toFormattedDateString()}}</td>
                                                   <td scope="col" class="text-center">{{$requisition->unit_measure}}</td>
                                                   <td scope="col" class="text-center">{{$requisition->quantity}}</td>
                                                   <td scope="col" class="text-right">{{number_format($requisition->unit_price,2)}}</td>
                                                   <td scope="col" class="text-right">{{number_format($requisition->vat_amount,2)}}</td>
                                                   <td scope="col" class="text-right">{{number_format($requisition->gross_amount,2)}}</td>

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
                                                <td scope="col" class="text-left">Total</td>
                                                <td scope="col" class="text-right">{{number_format(RequisitionsController::getRequisitionTotal($requisitionID->req_no),2)}}</td>


                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Amount Paid</td>
                                                <td scope="col" class="text-right">{{number_format($paid_amount,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Amount Retired</td>
                                                <td scope="col" class="text-right">{{number_format(($amount_retired + $amount_received),2)}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Amount Unretired</td>
                                                <td scope="col" class="text-right">
                                                  <?php if($amount_unretired < 0){echo 'N/A';}else{echo number_format($amount_unretired,2);} ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Balance Claimed</td>
                                                <td scope="col" class="text-right">
                                                  <?php if($amount_claimed < 0){echo 'N/A';}else{echo number_format($amount_claimed,2);} ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        @endif
                                        @if($requisitions2->isEmpty())
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
                                            <?php $req = Requisition::where('req_no', $req_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->where('budget_id',0)->get(); ?>
                                            @foreach($req as $req)
                                                <tr>
                                                   <td scope="col" class="text-center">{{$req->item_name}}</td>
                                                   <td scope="col" class="text-center">{{$req->description}}</td>
                                                   <td scope="col" class="text-center">{{$req->unit_measure}}</td>
                                                   <td scope="col" class="text-center">{{$req->quantity}}</td>
                                                   <td scope="col" class="text-right">{{number_format($req->unit_price,2)}}</td>
                                                   <td scope="col" class="text-right">{{number_format($req->vat_amount,2)}}</td>
                                                   <td scope="col" class="text-right">{{number_format($req->gross_amount,2)}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Total</td>
                                                <td scope="col" class="text-right">{{number_format(RequisitionsController::getRequisitionTotal($requisitionID->req_no),2)}}</td>

                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Amount Paid</td>
                                                <td scope="col" class="text-right">{{number_format($paid_amount,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Amount Retired</td>
                                                <td scope="col" class="text-right">{{number_format($retired_amount,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Amount Unretired</td>
                                                <td scope="col" class="text-right">
                                                  <?php if($amount_unretired < 0){echo 'N/A';}else{echo number_format($amount_unretired,2);} ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Balance Claimed</td>
                                                <td scope="col" class="text-right">
                                                  <?php if($amount_claimed < 0){echo 'N/A';}else{echo number_format($amount_claimed,2);} ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        @endif
                                    </table>

                                </div>

                        </div>
                        <div class="card-body">
                            <form class="form-inline" action="{{ route('return-receive-payments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_date" value="{{ now() }}">
                                <input type="hidden" name="req_id" value="{{ $requisitionID->id }}">
                                <input type="hidden" name="req_no" value="{{$requisitionID->req_no}}">
                                <!--  -->

                                        <div class="form-group">
                                            <input type="hidden" name="req_no" value="{{$requisitionID->req_no}}">
                                            <input style="width: 140px;" type="text" name="ref_no" class="form-control" placeholder="Enter Reference No." value="{{FinanceSupportiveDetailsController::generateReferenceNo()}}" data-toogle="tooltip" data-placement="top" title="Receipt Number (Automatic Generated)">
                                        </div>


                                        <div class="form-group">
                                            <input style="width: 190px;" type="text" name="cash_collector" class="form-control" placeholder="Cash Collector" data-toogle="tooltip" data-placement="top" title="Enter Cash Collector Name">
                                        </div>

                                        <div class="form-group">
                                            <input style="width: 130px;" type="number" name="amount_paid" class="form-control" placeholder="Amount Paid" data-toogle="tooltip" data-placement="top" title="Enter Amount to Pay">
                                        </div>


                                        <div class="form-group">
                                            <select name="account_id" class="form-control" data-toogle="tooltip" data-placement="top" title="Select Account">
                                                <option value="Account" selected disabled="">
                                                    Choose Account To Pay
                                                </option>
                                                @foreach($accounts as $account)
                                                    <option value="{{$account->id}}">{{$account->account_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <input style="width: 340px;" type="text" class="form-control" name="comment" placeholder="Comment" data-toogle="tooltip" data-placement="top" title="Add Comment"/>
                                        </div>

                                <button type="submit" class="btn btn-outline-primary">Add</button>
                                <!-- <a id="approveBtn" href="{{url('confirmation-form/'.$requisitionID->req_no)}}" class="btn btn-sm btn-twitter">
                                 Process Payment
                                </a> -->
                            </form>
                            @if($flash = session('message'))
                                <div id="flash" class="alert alert-info">
                                    {{ $flash }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
