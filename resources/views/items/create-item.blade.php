<?php
use App\Http\Controllers\Item\ItemController;
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
                                <div class="col-lg-6">
                                    <h4 class="card-title">Create Item</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-10">
                                <form method="POST" action="{{ route('items.store') }}">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="title_no" value="{{ $budget->title_no }}">

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                               <input type="text" name="item_name" class="form-control" placeholder="Item Name">
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                               <input type="number" name="unit_price" class="form-control" placeholder="Unit Price">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                               <input type="number" name="unit_measure" class="form-control" placeholder="Unit Measure">
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                               <input type="number" name="quantity" class="form-control" placeholder="Quantity">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <textarea class="form-control" name="description" placeholder="Item Description"></textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-primary">Add Item</button>
                                </form>
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
