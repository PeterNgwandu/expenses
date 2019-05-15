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
                                    <h4 class="card-title">Edit {{$item->item_name}}</h4>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                          <form method="POST" action="{{ route('items.update',$item->id) }}">
                                          @method('PUT')
                                          @csrf
                                          <div class="row">

                                              <input type="hidden" name="budget_id" value="{{$item->budget_id}}">
                                              <input type="hidden" id="item_no" name="item_no" class="form-control" placeholder="Item Number" value="{{$item->item_no}}">

                                              <div class="col-lg-3">
                                                  <div class="form-group">
                                                     <select name="account_id" class="form-control">
                                                      <option value="Select Account">Select Account</option>
                                                      @foreach($accounts as $account)
                                                       <option value="{{$account->id}}">{{$account->account_name}}</option>
                                                      @endforeach
                                                     </select>
                                                  </div>

                                              </div>

                                              <div class="col-lg-3">
                                                  <div class="form-group">
                                                     <input type="text" name="item_name" value="{{$item->item_name}}" class="form-control" placeholder="Item Name">
                                                  </div>

                                              </div>

                                              <div class="col-lg-2">
                                                  <div class="form-group">
                                                     <input type="number" name="unit_price" value="{{$item->unit_price}}" class="form-control" placeholder="Unit Price">
                                                  </div>

                                              </div>
                                              <div class="col-lg-2">
                                                  <div class="form-group">
                                                     <input type="text" name="unit_measure" value="{{$item->unit_measure}}" class="form-control" placeholder="Unit Measure">
                                                  </div>

                                              </div>
                                              <div class="col-lg-2">
                                                  <div class="form-group">
                                                     <input type="number" name="quantity" value="{{$item->quantity}}" class="form-control" placeholder="Quantity">
                                                  </div>

                                              </div>

                                          </div>

                                          <div class="row">
                                              <div class="col-lg-6">
                                                  <div class="form-group">
                                                  </div>

                                              </div>



                                          </div>

                                          <div class="row">


                                          </div>
                                          <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group">
                                                      <textarea class="form-control" name="description" placeholder="Item Description">{{$item->description}}</textarea>
                                                  </div>

                                              </div>
                                          </div>

                                          <button type="submit" class="btn btn-sm btn-primary">Edit Item</button>
                                      </form>
                        </div>

                        </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection
