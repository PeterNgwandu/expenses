<?php

use Illuminate\Support\Facades\Auth;
use App\Accounts\FinanceSupportiveDetail;
use App\Requisition\Requisition;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Requisitions\RequisitionsController;
use App\Http\Controllers\Accounts\FinanceSupportiveDetailsController;
use App\Http\Controllers\ExpenseRetirements\ExpenseRetirementController;

// foreach ($data as $data) :

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
                                    <h2 class="card-title">Pay Expense Retirement</h2>
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

                                        @if(!$expense_retirement->isEmpty())
                                        <thead>
                                            <tr>
                                                <th>Expense Retirement Details</th>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="text-center">Expense Retirement No.</th>
                                                <th scope="col" class="text-center">Budget</th>
                                                <th scope="col" class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                              <tr>
                                                  <td scope="col" class="text-center">{{$expense_retirement[0]->ret_no}}</td>
                                                  <td scope="col" class="text-center">{{$expense_retirement[0]->budget}}</td>
                                                  <td scope="col" class="text-center">{{$expense_retirement[0]->status}}</td>
                                              </tr>
                                        </tbody>
                                        @elseif($expense_retirement->isEmpty())
                                        <thead>
                                            <tr>
                                                <th>Expense Retirement Details</th>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="text-center">Expense Retirement No.</th>
                                                <th scope="col" class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                              <tr>
                                                  <td scope="col" class="text-center">{{$ex_retirement_no_budget[0]->ret_no}}</td>
                                                  <td scope="col" class="text-center">{{$ex_retirement_no_budget[0]->status}}</td>
                                              </tr>
                                        </tbody>
                                        @endif
                                    </table>
                                </div>
                        </div>
                        <div  class="col-lg-12">
                            <div class="col-lg-12 mt-2">
                                    <table class="table table-sm table-striped table-bordered">
                                        @if(!$expense_retirement->isEmpty())
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
                                            @foreach($expense_retirement as $retirement)
                                                <tr>
                                                   <td scope="col" class="text-left">{{$retirement->item}}</td>
                                                   <td scope="col" class="text-left">{{$retirement->item_name}}</td>
                                                   <td scope="col" class="text-left">{{$retirement->description}}</td>
                                                   <td scope="col" class="text-center">{{$retirement->created_at->toFormattedDateString()}}</td>
                                                   <td scope="col" class="text-center">{{$retirement->unit_measure}}</td>
                                                   <td scope="col" class="text-center">{{$retirement->quantity}}</td>
                                                   <td scope="col" class="text-right">{{number_format($retirement->unit_price,2)}}</td>
                                                   <td scope="col" class="text-right">{{number_format($retirement->vat_amount,2)}}</td>
                                                   <td scope="col" class="text-right">{{number_format($retirement->gross_amount,2)}}</td>

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
                                                <td scope="col" class="text-right">{{number_format(ExpenseRetirementController::getExpenseRetirementTotal($retirement->ret_no),2)}}</td>
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
                                                <td scope="col" class="text-right">{{number_format($amount_paid,2)}}</td>
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
                                                <td scope="col" class="text-right">{{number_format(ExpenseRetirementController::getExpenseRetirementTotal($retirement->ret_no),2)}}</td>
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
                                                <td scope="col" class="text-right">{{number_format($balance,2)}}</td>
                                            </tr>
                                        </tbody>
                                        @endif
                                        @if($expense_retirement->isEmpty())
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

                                            @foreach($ex_retirement_no_budget as $retirement)
                                                <tr>
                                                   <td scope="col" class="text-left">{{$retirement->item_name}}</td>
                                                   <td scope="col" class="text-left">{{$retirement->description}}</td>
                                                   <td scope="col" class="text-center">{{$retirement->unit_measure}}</td>
                                                   <td scope="col" class="text-center">{{$retirement->quantity}}</td>
                                                   <td scope="col" class="text-right">{{number_format($retirement->unit_price,2)}}</td>
                                                   <td scope="col" class="text-right">{{number_format($retirement->vat_amount,2)}}</td>
                                                   <td scope="col" class="text-right">{{number_format($retirement->gross_amount,2)}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Total</td>
                                                <td scope="col" class="text-right">{{number_format(ExpenseRetirementController::getExpenseRetirementTotal($retirement->ret_no),2)}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Amount Paid</td>
                                                <td scope="col" class="text-right">{{number_format($amount_paid,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Amount Retired</td>
                                                <td scope="col" class="text-right">{{number_format(ExpenseRetirementController::getExpenseRetirementTotal($retirement->ret_no),2)}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-left">Balance Claimed</td>
                                                <td scope="col" class="text-right">{{number_format($balance,2)}}</td>
                                            </tr>
                                        </tbody>
                                        @endif
                                    </table>

                                </div>

                        </div>
                        <div class="card-body">
                            <form class="form-inline" action="{{ route('expense-retirement-payments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ret_no" value="{{$ret_no}}">
                                <!--  -->

                                        <div class="form-group">
                                            <input disabled style="width: 140px;" type="text" name="ref_no" class="form-control" placeholder="Enter Reference No." value="{{ExpenseRetirementController::generateReferenceNo()}}" data-toogle="tooltip" data-placement="top" title="Receipt Number (Automatic Generated)">
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
                                            <input style="width: 362px;" type="text" class="form-control" name="comment" placeholder="Comment" data-toogle="tooltip" data-placement="top" title="Add Comment"/>
                                        </div>

                                <button type="submit" class="btn btn-twitter">Pay</button>

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
