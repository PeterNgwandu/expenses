<?php

use App\Http\Controllers\Reports\ReportsController;

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
                                    <h4 class="card-title">Refunds Received</h4>
                                </div>
                            </div>
                        </div>

                          <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-12">

		                          <div class="col-lg-8 mb-3 ml-3">
		                        	<h6>Custom Filtering</h6>
		                        	     <form action="{{ url('refunds_received-custom-filter/'.$from.'/'.$to) }}" method="POST" class="form-inline">
                                        @csrf
                                        <div class="input-group input-group--inline">
                                            <div class="input-group-addon">
                                                <i class="material-icons">date_range</i>
                                            </div>
                                            <input type="text" class="form-control datepicker" name="from" placeholder="From" value="">&nbsp;
                                        </div>
                                        <div class="input-group input-group--inline">
                                            <div class="input-group-addon">
                                                <i class="material-icons">date_range</i>
                                            </div>
                                            <input type="text" class="form-control datepicker" name="to" placeholder="To" value="">&nbsp;
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-twitter">Filter</button>&nbsp;&nbsp;
                                    </form>

                                    <!-- <form method="POST" action="{{url('/grouping-received-funds')}}" class="mt-2 form-inline">
                                        @csrf
                                        <select class="form-control" name="req_no">
                                          <option disabled>Grouping</option>
                                          @foreach($balance_received_req_no as $balance)
                                            <option value="{{$balance->req_no}}">{{$balance->req_no}}</option>
                                          @endforeach
                                        </select>&nbsp;&nbsp;
                                        <button type="submit" class="btn btn-sm btn-facebook">Group By Requisition No.</button>&nbsp;&nbsp;
                                    </form> -->
		                            </div>
                                <div class="mb-3 ml-2">
                                  <a style="border-radius:0 !important;background: #39A16C;" target="__blank" class="btn btn-sm text-white
                                     ml-3" from="{{$from}}" to="{{$to}}" href="{{route('export-refunds-received',['from' => $from, 'to' => $to])}}">
                                    <span>
                                        <i style="cursor: pointer;" class="material-icons  md-2 align-middle">widgets</i>
                                      </span>
                                      Export Excel
                                    </a>  
                                  <a style="border-radius:0 !important;" target="__blank" class="btn btn-sm
                                       btn-primary print-imprest-report" from="{{$from}}" to="{{$to}}" href="{{url('/refunds-received-pdf-report/'.$from.'/'.$to)}}">
                                    <span>
                                        <i style="cursor: pointer;" class="material-icons  md-2 align-middle">print</i>
                                    </span>
                                    Print Report
                                  </a>
                                  <a href="{{url('/all-refunds_received')}}" style="border-radius:0 !important;" class="mt -2 btn  btn-sm btn-facebook">
                                    <span>
                                        <i style="cursor: pointer;" class="material-icons  md-2 align-middle">cached</i>
                                    </span>
                                  Refresh All Requisitions</a>
                                </div>
                                <table id="data-table" class="table table-sm table-bordered table-striped table-dark mb-0">
                                    <thead>
                                <tr>
                                    <th scope="col" class="text-center">Req No.</th>
                                    <th scope="col" class="text-center">Requester</th>
                                    <th scope="col" class="text-center">Department</th>
                                    <th scope="col" class="text-center">Activity Name</th>
                                    <th scope="col" class="text-center">Date Requested</th>
                                    <th scope="col" class="text-center">Received Date</th>
                                    <th scope="col" class="text-center">Amount Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                  @foreach($balance_received as $balance)
                                    <tr>
                                        <td scope="col" class="text-left">
                                            <a style="text-decoration: none;" class="text-info" href="{{url('submitted-requisitions',$balance->req_no)}}">{{ $balance->req_no }}</a>
                                        </td>
                                        <td scope="col" class="text-left">{{ $balance->username }}</td>
                                        <td scope="col" class="text-left">{{ $balance->department }}</td>
                                        <td scope="col" class="text-left">{{ $balance->activity_name }}</td>
                                        <td scope="col" class="text-left">{{ date('Y-m-d',strtotime($balance->created_at)) }}</td>
                                        <td scope="col" class="text-left">{{ $balance->payment_date }}</td>
                                        <td scope="col" class="text-right">{{ number_format($balance->amount_paid,2) }}</td>
                                    </tr>
                                  @endforeach
                                    
                            </tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td scope="col" class="text-info">Total Balance Received</td>
                                    <td scope="col" class="text-right">
                                      {{number_format(ReportsController::refundsReceivedTotalBasedOnPaymentDate($from, $to),2)}}
                                    </td>
                                </tr>
                            </table>
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

