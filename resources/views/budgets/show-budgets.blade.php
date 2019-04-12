<?php
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
?>

@extends('layout.app')
<style media="screen">
    .mycontainer {
        max-width: 96% !important;
    }
</style>
@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container mycontainer">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Budget</h4>
                                    <a href="#" class="float-right btn btn-primary" data-toggle="modal" data-target="#add_items">Add Items</a>
                                </div>
                            </div>
                        </div>

                           <table class="table  table-sm table-bordered table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Budget No.</th>
                                            <th scope="col" class="text-center">Title</th>
                                            <th scope="col" class="text-center">Description</th>
                                            <th scope="col" class="text-center">Date Created</th>
                                            <th scope="col" class="text-center">Total</th>
                                            <th scope="col" class="text-center">Commited</th>
                                            <th scope="col" class="text-center">Amount Spent</th>
                                            <th scope="col" class="text-center">Budget Balance</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach($budgets as $budget)
                                        <tr>
                                            <td class="align-middle text-center">{{ $budget->title_no }}</td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('budgets.show', $budget->id) }}">{{ $budget->title }}</a>
                                            </td>
                                            <td class="align-middle text-center">{{$budget->description}}</td>
                                            <td class="align-middle text-center">{{$budget->created_at->toFormattedDateString()}}</td>
                                            <td class="align-middle text-center">{{number_format(BudgetsController::get_sumitems_by_budgetID($budget->id))}}</td>
                                            <td class="align-middle text-center">{{number_format(BudgetsController::getCommitedAmount($budget->id))}}</td>
                                            <td class="align-middle text-center">{{number_format(BudgetsController::getSpentAmount($budget->id))}}</td>
                                            <td class="align-middle text-center">{{number_format(BudgetsController::getBudgetBalance($budget->id))}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{url('budgets/create')}}" class="float-right">Add Another Budget</a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<div class="modal fade" id="add_items" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Add Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                    <form method="POST" action="{{ route('items.store') }}">
                                    @csrf
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                               <select id="budget" name="budget_id" class="form-control">
                                                <option value="Select Budget Line" selected disabled>Select Budget</option>
                                                   @foreach($budgets as $budget_line)
                                                    <option value="{{$budget_line->id}}">{{$budget_line->title}}</option>
                                                   @endforeach
                                               </select>
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                               <select name="account_id" class="form-control">
                                                <option value="Select Account">Select Account</option>
                                                   @foreach($accounts as $account)
                                                    <option value="{{$account->id}}">{{$account->account_name}}</option>
                                                   @endforeach
                                               </select>
                                            </div>

                                        </div>



                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                               <input type="text" id="item_no" name="item_no" class="form-control" value="" placeholder="Item Number">
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                               <input type="text" name="item_name" class="form-control" placeholder="Item Name">
                                            </div>

                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                               <input type="number" name="unit_price" class="form-control" placeholder="Unit Price">
                                            </div>

                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                               <input type="text" name="unit_measure" class="form-control" placeholder="Unit Measure">
                                            </div>

                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                               <input type="number" name="quantity" class="form-control" placeholder="Quantity">
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea class="form-control" name="description" placeholder="Item Description"></textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-primary">Add Item</button>
                                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
