<?php
use App\StaffLevel\StaffLevel;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Reports\ReportsController;

?>
@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container" style="max-width: 98% !important;">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Report for {{$budget->title}} </h4>
                                </div>

                            </div>
                            <a style="border-radius:0 !important; margin-left: 10px !important;" target="__blank" class="btn btn-sm btn-primary print-budget-report float-right ml-5 mt-2" budget_id="{{$budget_id}}" href="{{url('/budget-report/'.$budget_id)}}">
                                <span>
                                    <i style="cursor: pointer;" class="material-icons  md-2 align-middle">print</i>
                                </span>
                                Print
                            </a>
                            <a style="border-radius:0 !important; margin-left: 10px !important; background: #218452;" target="__blank" class="btn btn-sm text-white float-right ml-5 mt-2" href="{{route('export-budgets',$budget_id)}}">
                                <span>
                                    <i style="cursor: pointer;" class="material-icons  md-2 align-middle">widgets</i>
                                </span>
                                Export Excel
                            </a>
                        </div>
                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-12">
                                <table  class="table table-sm table-bordered table-striped mb-0">
                                    <thead>
                                <tr>
                                    <th scope="col" class="text-center">Item No.</th>
                                    <th scope="col" class="text-center">Item Name</th>
                                    <th scope="col" class="text-center">Total Allocated</th>
                                    <th scope="col" class="text-center">Amount Uncommitted</th>
                                    <th scope="col" class="text-center">Amount Committed</th>
                                    <!-- <th scope="col" class="text-center">Amount Not Spent</th> -->
                                    <th scope="col" class="text-center">Amount Spent</th>
                                    <!-- <th scope="col" class="text-center">Net Amount Spent</th> -->
                                    <th scope="col" class="text-center">VAT</th>
                                    <th scope="col" class="text-center">Gross Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($budgets as $budget)
                                <tr>
                                    <td style="width: 30px;" class="align-middle text-left">{{$budget->item_no}}</td>
                                    <td style="width: 220px;" class="align-middle text-left">{{$budget->item_name}}</td>
                                    <td class="align-middle text-right">
                                        {{number_format($budget->unit_price * $budget->quantity,2)}}
                                    </td>
                                    <td class="text-right">
                                        {{number_format(ReportsController::calculateUncommitedAmount($budget_id,$budget->id),2)}}
                                    </td>
                                    <td class="align-middle text-right">
                                        @if(number_format(ReportsController::calculateCommittedAmount($budget_id,$budget->id) > 0))
                                            {{number_format(ReportsController::calculateCommittedAmount($budget_id,$budget->id),2)}}
                                        @else
                                            {{number_format(0,2)}}
                                        @endif    
                                    </td>
                                    <!-- <td class="align-middle text-right">
                                        @if(ReportsController::calculateNotSpentAmount($budget_id,$budget->id) > 0)
                                            {{number_format(ReportsController::calculateNotSpentAmount($budget_id,$budget->id),2)}}
                                        @else
                                            {{number_format(0,2)}}
                                        @endif        
                                        
                                    </td> -->
                                    <td class="align-middle text-right">
                                        @if(ReportsController::calculateAmountSpent($budget_id,$budget->id) > 0)
                                            {{number_format(ReportsController::calculateAmountSpent($budget_id,$budget->id),2)}}
                                        @else
                                            {{number_format(0,2)}}
                                        @endif        
                                    </td>



                                    <!-- <td class="align-middle text-right">
                                        {{number_format(ReportsController::calculateAmountSpent($budget_id,$budget->id),2)}}
                                    </td> -->
                                    <td class="align-middle text-right">
                                        {{number_format(ReportsController::calculateVATByItem($budget_id,$budget->id),2)}}
                                    </td>


                                    
                                    <td class="text-right">
                                        {{number_format(ReportsController::calculateGrossSpent($budget_id,$budget->id),2)}}
                                    </td>
                                </tr>
                               @endforeach 
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right">Total</td>
                                    <td class="text-right">
                                        {{number_format(ReportsController::totalBudgetById($budget_id),2)}}
                                    </td>
                                    <td class="text-right">
                                        {{number_format(ReportsController::totalUncommitted($budget->budget_id),2)}}
                                    </td>
                                    <td class="text-right">
                                        {{number_format(ReportsController::totalCommitted($budget->budget_id),2)}}
                                    </td>
                                    <!-- <td class="text-right">
                                        @if(ReportsController::totalNotSpent($budget->budget_id) > 0)
                                            {{number_format(ReportsController::totalNotSpent($budget->budget_id),2)}}
                                        @else
                                            {{number_format(0,2)}}
                                        @endif        
                                    </td> -->
                                    <td class="text-right">
                                        @if(ReportsController::totalSpent($budget->budget_id) > 0)
                                            {{number_format(ReportsController::totalSpent($budget->budget_id),2)}}
                                        @else
                                            {{number_format(0,2)}}
                                        @endif    
                                    </td>



                                    <!-- <td class="text-right">
                                        {{number_format(ReportsController::totalSpent($budget->budget_id),2)}}
                                    </td> -->
                                    <td class="text-right">
                                        {{number_format(ReportsController::calculateVAT($budget_id),2)}}
                                    </td>



                                    
                                    <td class="text-right">
                                        {{number_format(ReportsController::totalGrossSpent($budget->budget_id) + ReportsController::calculateVATByItem($budget_id,$budget->id),2)}}
                                    </td>
                                </tr>
                               
                            </tbody>

                            </table>
                                <div class="row float-right mt-3">
                                    <div class="col col-lg-12">
                                        <div>
                                            <p>Total Amount:&nbsp;&nbsp; <span class="float-right font-weight-bold">{{number_format(ReportsController::totalBudgetById($budget_id),2)}}</span>
                                            </p>
                                            @if(ReportsController::amountAvailable($budget_id) < 0)
                                                <span>Available Amount: <span class="text-danger">Budget Over spent</span></span>
                                            @else
                                                <p>Available Amount :&nbsp;&nbsp; <span class="float-right font-weight-bold">{{number_format(ReportsController::amountAvailable($budget_id) - ReportsController::calculateVAT($budget_id),2)}}</span></p>
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
        </div>

    </div>
</div>

@endsection

