<?php 

use App\Http\Controllers\Requisitions\RequisitionsController;

 ?>
@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">


            <div class="row">
                <div class="col-lg-12">
                    <div class="">
                        <div class="col-md-11 mr-2 mb-3 ml-3">
                            <h4>Retirement ID<span class="text-primary">#{{$retirement->id}}</span></h4> 
                            <h4 class="float-right mr-10">Retirement Form No. <span class="text-primary">{{$retirement->ret_no}}</span></h4>
                            <div class="mb-1"><span class="text-muted">Retirement Date:</span> {{$retirement->created_at->toFormattedDateString()}}</div>
                            <div class="mb-1"><span class="text-muted">Requested Item: </span>{{$retirement->item_name}}</div>
                            <div class="mb-1"><span class="text-muted">Description: </span>{{$retirement->description}}</div>
                            <div><span class="text-muted">VAT:</span> <span class="badge badge-success">{{$retirement->vat}}</span></div><br>

                            <div class=""><span class="text-muted">VAT Amount:</span> <span class="badge badge-success">{{$retirement->vat_amount == 0.00 ? 'NIL' : $retirement->vat_amount}}</span></div><br>

                            <div><span class="text-muted">Gross Amount:</span> <span class="badge badge-success">{{$retirement->gross_amount}}</span></div><br>

                            <div><span class="text-muted">Requisition status:</span> <span class="badge badge-success">{{$retirement->status}}</span></div>

                        </div>
                    </div>

                <div class="container-fluid">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-body">

                                <h6 class="mb-0 text-primary">REQUESTER:</h6>
                                <p class="lead">{{$user->username}}</p>

                                <div class="mb-1">Phone: {{$user->phone}}</div>
                                <div class="mb-1">Phone2: {{$user->phone_alternative}}</div>
                                <div>Email: <a href="#">{{$user->email}}</a></div>
                                <br>
                                <h5 class="text-danger">Comments</h5>
                                @if(!empty($retirement_comments->body))
                                    
                                    <li class="list-unstyled">{{ $retirement_comments->body }}</li>
                                    <!-- <a href="{{route('retirements.edit',$retirement->id)}}" data-toggle="modal" data-target="#add_items">Edit</a> -->
                                    
                                @else
                                    <li class="list-unstyled">No Comments</li>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="card">
                        
                    </div>
                    
                </div>


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
                    <h5 class="modal-title" id="largeModalLabel">Edit Retirement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                  <form method="POST" action="{{route('retirements.update',$retirement->id)}}">

                                    @method('PUT')
                                    @csrf
                                    <input type="hidden" name="req_no">
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn  float-right btn-outline-primary mt-3">Edit</button>
                                             <select style="width: 154px;background: #ffffff;border: 1px solid #566573" id="budget" name="budget_id" class="form-control budget">
                                                 <option value="Select Budget" selected disabled>
                                                     Select Budget
                                                 </option>
                                                
                                             </select>
                                        </div>
                                    </div>
                                    <div class="row requisition mt-3">
                                        <div class="col-4">
                                             <select id="item" name="item_id" class="form-control item requisition-item">
                                                 <option value="Select Budget Line" selected disabled>
                                                    Budget Line
                                                 </option>
                                                
                                             </select>
                                        </div>
                                            <div class="col-4">
                                                <input type="text" value="" class="form-control" placeholder="Budget Line Description">
                                            </div>
                                            <div class="col-4">
                                                <input type="text" name="item_name" class="form-control item_name" placeholder="Item Name" value="{{$retirement->item_name}}">
                                            </div>
                                        </div>

                                                                         

                                    <div class="row mt-3">
                                        <div class="col-4">
                                            <input type="text" name="description" class="form-control" placeholder="Item Description" value="{{$retirement->description}}">
                                        </div>

                                        <div class="col-2">
                                            <input type="text" name="unit_measure" class="form-control" placeholder="Unit of Measure" value="{{$retirement->unit_measure}}">
                                        </div>
                                        <div class="col-3">
                                            <input type="number" name="quantity" class="form-control" placeholder="Quantity" value="{{$retirement->quantity}}">
                                        </div>
                                        <div class="col-3">
                                            <input type="number" name="unit_price" class="form-control" placeholder="Unit Price" value="{{$retirement->unit_price}}">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <select name="vat" value="{{$retirement->vat}}" class="form-control">
                                                <option value="VAT Options" selected disabled>VAT Options</option>
                                                <option value="VAT Exclusive">VAT Exclusive</option>
                                                <option value="VAT Inclusive">VAT Inclusive</option>
                                                <option value="Non VAT">Non VAT</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select name="account_id" class="form-control accounts">
                                                <option value="VAT Options" selected disabled>Select Account</option>
                                                
                                            </select>
                                        </div>
                                    </div>    
                                        
                                </form>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
