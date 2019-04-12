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
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Submitted Requisitions</h4>
                                    <!-- <a href="{{route('requisitions.create')}}" class="float-right btn btn-outline-primary">Edit</a> -->
                                </div>
                            </div>
                        </div>
                            <table id="data-table" class="table table-sm table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Budget Line</th>
                                            <th scope="col" class="text-center">Item Name</th>
                                            <th scope="col" class="text-center">Account</th>
                                            <th scope="col" class="text-center">Requester</th>
                                            <th scope="col" class="text-center">Department</th>
                                            <th scope="col" class="text-center">Unit Measure</th>
                                            <th scope="col" class="text-center">Unit Price</th>
                                            <th scope="col" class="text-center">Quantity</th>
                                            <th scope="col" class="text-center">VAT Amount</th>
                                            <th scope="col" class="text-center">Gross Amount</th>
                                            <th scope="col" class="text-center">Approval Status</th>
                                            <th scope="col" class="text-center">Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($Submittedrequisitions as $requisition)
                                            <tr>
                                                <td scope="col" class="text-center">{{$requisition->budget}}</td>
                                                <td scope="col" class="text-center">{{$requisition->item}}</td>
                                                <td scope="col" class="text-center">{{$requisition->account}}</td>
                                                <td scope="col" class="text-center">{{$requisition->username}}</td>
                                                <td scope="col" class="text-center">{{$requisition->department}}</td>
                                                <td scope="col" class="text-center">{{$requisition->unit_measure}}</td>
                                                <td scope="col" class="text-center">{{$requisition->unit_price}}</td>
                                                <td scope="col" class="text-center">{{$requisition->quantity}}</td>
                                                <td scope="col" class="text-center">{{$requisition->vat_amount == 0.00 ? 'NIL' : $requisition->vat_amount}}</td>
                                                <td scope="col" class="text-center">{{number_format($requisition->gross_amount,2)}}</td>
                                               
                                                <td scope="col" class="text-center">
                                                    <button class="btn btn-sm btn-outline-warning">{{$requisition->status}}</button>
                                                </td>
                                                <td style="width: 150px;" scope="col" class="text-center">
                                                    <a href="{{url('requisition-summary/'.$requisition->id)}}" class="btn btn-sm btn-outline-warning">View</a>
                                                    <a href="{{url('approve-requisition/'.$requisition->id)}}" class="btn btn-sm btn-primary">Approve</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td scope="col" class="text-center font-weight-bold text-success">TOTAL</td>
                                        <td scope="col" class="text-success text-center font-weight-bold">
                                                    {{ RequisitionsController::getTotalofTotals($requisition->user_id) }} /=
                                                </td>
                                        <!-- @if($Submittedrequisitions->isNotEmpty()) -->
                                        <td scope="col" class="text-center">
                                            <!-- <button data-id={{ Auth::user()->id }} id="submit-requisition" type="button" class="btn btn-outline-success">Submit</button> -->
                                            <!-- <a href="{{url('submit-requisition-form/'.Auth::user()->id)}}">submit</a> -->
                                        </td>
                                        <!-- @endif -->
                                    </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>