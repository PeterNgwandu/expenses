<?php
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\StaffLevel\StaffLevel;

$stafflevels = StaffLevel::all();

$hod = $stafflevels[0]->id;
$ceo = $stafflevels[1]->id;
$supervisor = $stafflevels[2]->id;
$normalStaff = $stafflevels[3]->id;
$financeDirector = $stafflevels[4]->id;

?>
@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">{{ $budget->title }} Budget</h4>
                                    @if(Auth::user()->username == 'Admin' || Auth::user()->stafflevel_id == $hod || Auth::user()->stafflevel_id == $financeDirector || Auth::user()->stafflevel_id == $ceo)
                                      <a href="#" class="float-right btn btn-primary" data-toggle="modal" data-target="#add_items">Add Items</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                            <table id="makeEditable" class="table table-sm table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Item No.</th>
                                            <th scope="col" class="text-center">Item Name</th>
                                            <th scope="col" class="text-center">Unit Measure</th>
                                            <th scope="col" class="text-center">Description</th>
                                            <th scope="col" class="text-center">Unit Price</th>
                                            <th scope="col" class="text-center">Quantity</th>
                                            <th scope="col" class="text-center">Total</th>
                                            @if(Auth::user()->username == 'Admin' || Auth::user()->stafflevel_id == $hod || Auth::user()->stafflevel_id == $financeDirector || Auth::user()->stafflevel_id == $ceo)
                                             <th scope="col" class="text-center">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($itemsUnderBudget as $item)
                                        <tr>
                                            <td class="align-middle text-center">{{ $item->item_no }}</td>
                                            <td class="align-middle text-center">{{ $item->item_name }}</td>
                                            <td class="align-middle text-center">{{ $item->unit_measure }}</td>
                                            <td class="align-middle text-center">{{ $item->description }}</td>
                                            <td class="align-middle text-center">{{ number_format($item->unit_price) }}</td>
                                            <td class="align-middle text-center">{{ number_format($item->quantity,0) }}</td>
                                            <td class="align-middle text-center">{{ number_format($item->unit_price * $item->quantity) }}</td>
                                            @if(Auth::user()->username == 'Admin' || Auth::user()->stafflevel_id == $hod || Auth::user()->stafflevel_id == $financeDirector || Auth::user()->stafflevel_id == $ceo)
                                            <td class="align-middle text-center">
                                              <a class="edit-item" href="{{url('edit-item/'.$item->id)}}" id="">
                                                <span>
                                                     <i style="cursor: pointer;" class="material-icons md-10 align-middle mb-1 text-warning">edit</i>
                                        				</span>
                                              </a>
                                            </td>
                                            @endif
                                        </tr>

                                       @endforeach
                                       <tr>
                                            <td class="align-middle text-center"></td>
                                            <td class="align-middle text-center"></td>
                                            <td class="align-middle text-center"></td>
                                            <td class="align-middle text-center"></td>
                                            <td class="align-middle text-center"></td>
                                            <td class="align-middle text-center text-success font-weight-bold">TOTAL</td>
                                            <td class="align-middle text-center text-danger font-weight-bold">{{ isset($item->budget_id) ? number_format(BudgetsController::totalBudgetById($item->budget_id)) : 0 }}</td>
                                            @if(Auth::user()->username == 'Admin' || Auth::user()->stafflevel_id == $hod || Auth::user()->stafflevel_id == $financeDirector || Auth::user()->stafflevel_id == $ceo)
                                              <td class="align-middle text-center"></td>
                                            @endif
                                      </tr>
                                    </tbody>
                                </table>
                        </div>
                        <a href="{{url('budgets')}}" class="float-left">View All Budget</a>
                        @if(Auth::user()->username == 'Admin')
                        <a href="{{url('budgets/create')}}" class="float-right">Add Another Budget</a>
                        @endif
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

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                               <select id="budget" name="budget_id" class="form-control">
                                                <option value="Select Budget Line" selected disabled>Select Budget</option>
                                                   @foreach($budgets as $budget_line)
                                                    <option value="{{$budget_line->id}}">{{$budget_line->title}}</option>
                                                   @endforeach
                                               </select>
                                            </div>

                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                               <select name="account_id" class="form-control">
                                                <option value="Select Account">Select Account</option>
                                                   @foreach($accounts as $account)
                                                    <option value="{{$account->id}}">{{$account->account_name}}</option>
                                                   @endforeach
                                               </select>
                                            </div>

                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                               <input type="text" name="item_name" class="form-control" placeholder="Item Name">
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                               <input type="hidden" id="item_no" name="item_no" class="form-control" placeholder="Item Number" value="{{ItemController::generateItemNo($id)}}">
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
