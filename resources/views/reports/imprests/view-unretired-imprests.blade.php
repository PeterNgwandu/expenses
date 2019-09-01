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
                                    <h4 class="card-title">Unretired Imprests</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-12">

                            	<!-- <div class="col-lg-4 mb-3 ml-3">
                              		<h6>Filter based on aging</h6>
		                        	<select class="form-control aging-data-filter">
		                        		<option value="">Select Filtering Duration</option>
		                        		<option value="365">More than 1 year</option>
		                        		<option value="180">Between 180 - 265 Days</option>
		                        		<option value="60">Between 60 - 180 Days</option>
		                        		<option value="30">Between 30 - 60 Days</option>
		                        	</select>
		                        </div> -->

		                        <div class="col-lg-8 mb-3 ml-3">
		                        	<h6>Custom Filtering</h6>
		                        	<form action="{{ url('unretired-imprest-custom-filter/'.$from.'/'.$to) }}" method="POST" class="form-inline">
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
                                        <button type="submit" class="btn btn-sm btn-twitter">Filter</button>
                                    </form>
		                        </div>

                                <table id="data-table" class="table table-sm table-bordered table-striped table-dark mb-0">
                                    <thead>
                                <tr>
                                    <th scope="col" class="text-center">Req No.</th>
                                    <th scope="col" class="text-center">Requester</th>
                                    <th scope="col" class="text-center">Department</th>
                                    <th scope="col" class="text-center">Activity Name</th>
                                    <th scope="col" class="text-center">Date Requested</th>
                                    <th scope="col" class="text-center">Paid Date</th>
                                    <th scope="col" class="text-center">Total Requested</th>
                                    <th scope="col" class="text-center">Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($unretired_imprest as $imprest)
                                <tr>
                                    <td class="text-left text-info">                                  
                                        <a href="{{url('submitted-requisitions/'.$imprest->req_no)}}" class="text-info" style="text-decoration: none;">
                                          {{ $imprest->req_no }}
                                        </a>
                                    </td>
                                    <td class="text-left">{{ $imprest->requester }}</td>
                                    <td class="text-left">{{ $imprest->department }}</td>
                                    <td class="text-left">{{ $imprest->activity_name }}</td>
                                    <td class="text-left">{{ date('Y-m-d', strtotime($imprest->created_at)) }}</td>
                                    <td class="text-left">{{ $imprest->payment_date }}</td>

                                    <td class="text-right">{{ number_format(ReportsController::calculateAmountRequested($imprest->req_no),2) }}</td>

                                    <td class="text-right">{{ number_format(ReportsController::calculateAmountPaid($imprest->req_no),2) }}</td>
                                </tr>
                               @endforeach
                                
                            </tbody>
                            	<tr>
                               		<td></td>
                               		<td></td>
                               		<td></td>
                               		<td></td>
                               		<td></td>
                               		<td></td>
                               		<td class="float-right font-weight-bold text-info">Total Unretired</td>
                               		<td class="text-right">{{ number_format(ReportsController::calculateTotalUnretiredImrestsCostsBasedOnFilter($from,$to),2) }}</td>
                               		<tr>
	                               		<td></td>
	                               		<td></td>
	                               		<td></td>
	                               		<td></td>
	                               		<td></td>
	                               		<td></td>
	                               		<td>
                                      <a style="border-radius:0 !important;background: #39A16C;" target="__blank" class="btn btn-sm text-white
                                         ml-3" from="{{$from}}" to="{{$to}}" href="{{route('export-imprests',['from' => $from, 'to' => $to])}}">
                                        <span>
                                            <i style="cursor: pointer;" class="material-icons  md-2 align-middle">widgets</i>
                                          </span>
                                          Export Excel
                                        </a>  
                                    </td>
	                               		<td >
	                               			<a style="border-radius:0 !important;" target="__blank" class="btn btn-sm
                                           btn-primary print-imprest-report" from="{{$from}}" to="{{$to}}" href="{{url('/unretired-imprests-report/'.$from.'/'.$to)}}">
                                        <span>
                                            <i style="cursor: pointer;" class="material-icons  md-2 align-middle">print</i>
                                        </span>
                                        Print Report
                                      </a>
	                               		</td>
                               		</tr>
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

