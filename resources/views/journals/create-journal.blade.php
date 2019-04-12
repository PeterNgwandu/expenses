<?php

use Illuminate\Support\Carbon;
use App\Http\Controllers\Journal\JournalController;

?>

@extends('layout.app')

@section('content')
<div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">
        <div class="container journals-card">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h2 class="card-title">Create Journals</h2>
                                    <!-- <button id="print" class="btn btn-primary float-right">Print</button> -->
                                    <a class="btn btn-primary print float-right" href="{{url('/journal/report')}}">Print</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                          	<div class="row mr-3">
                          		<div class="col-12 ml-3 mr-3" >
                                    <div class="row align-items-center">
                                        	<div class="col-lg-2">
                                    			<p class="lead">Imprest (Advance).</p>
                                        		<form method="POST" action="{{url('/journal/post')}}" class="form-block">
                                        		@csrf
                                        		<div class="form-group">
                                        			<input id="journal_no" type="text" name="journal_no" class="form-control input-sm" value="{{JournalController::generateJournalNo()}}">
                                        		</div>
                                        		<!-- <div id="date" class="form-group journal-form">
                                        			<input type="text" value="{{Carbon::now()->toFormattedDateString()}}" name="date" class="form-control input-sm datepicker">
                                        		</div> -->
                                        		<div class="form-group journal-form">
                                        			<select id="currency" name="currency" class="form-control input-sm">
                                        				<option value="Currency">Select Currency</option>
                                        				<option value="Tsh" selected >Tsh</option>
                                        				<option value="Ksh">Ksh</option>
                                        				<option value="Ush">Ush</option>
                                        				<option value="US Dollar">US Dollar</option>
                                        			</select>
                                        		</div>
                                        		@foreach($requisitions as $req)
                                                	<input id="req_no" type="hidden" name="req_no[]" value="{{$req->req_no}}">
                                            	@endforeach
                                        	</form>
                                        	</div>
                                        <div class="col-lg-12 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                            	
                                                <thead style="max-width: 30px;">
                                                    <tr>
                                                        <th scope="col" class="text-center">Date</th>
                                                        <th scope="col" class="text-center">Req #</th>
                                                    	<th scope="col" class="text-center">User</th>
                                                    	<th scope="col" class="text-center">Activity Name</th>
                                                    	<th scope="col" class="text-center">GL Accounts</th>
                                                    	<!-- <th scope="col" class="text-center">Currency</th> -->
                                                    	<th scope="col" class="text-center">DR</th>
                                                    	<th scope="col" class="text-center">CR</th>
                                                    	<!-- <th scope="col" class="text-center">Balance</th> -->
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                	
                                                    @foreach($requisitions as $req)
                                                    	
                                                    	<tr>                                                   	
                                                            <td scope="col" class="text-center">{{$req->created_at}}</td>
                                                            <td scope="col" class="text-center">{{$req->req_no}}</td>
                                            				<td scope="col" class="text-center">{{$req->username}}</td>
                                            				
                                                			<td scope="col" class="text-center">{{$req->activity_name}}</td>                                                			
                                                    		<td scope="col" class="text-center">{{$req->account_no}}</td>
                                                    		<!-- <td scope="col" class="text-center">Tz</td> -->
                                                    		<td scope="col" class="text-center">{{number_format($req->gross_amount)}}</td>
                                                            <td></td>
                                                		    <!-- <td></td> -->
                                                    	</tr>
                                                        <tr>
                                                            <td scope="col" class="text-center"></td>
                                                            <td scope="col" class="text-center"></td>
                                                            <td scope="col" class="text-center"></td>
                                                            
                                                            <td scope="col" class="text-center"></td>                                                            
                                                            <td scope="col" class="text-center">{{$req->account}}</td>
                                                            <!-- <td scope="col" class="text-center">Tz</td> -->
                                                            <td scope="col" class="text-center"> - </td>
                                                            <td scope="col" class="text-center">{{number_format(-$req->gross_amount)}}</td>
                                                            <!-- <td scope="col" class="text-center">{{number_format($req->gross_amount + (-$req->gross_amount))}}</td> -->
                                                        </tr>

                                                @endforeach
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td scope="col" class="text-center font-weight-bold">Total</td>
                                                            <td scope="col" class="text-center">{{number_format($requisitions[0]->amount_paid)}}</td>
                                                            <td scope="col" class="text-center">{{ number_format(-$requisitions[0]->amount_paid)}}</td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                            <table class="table table-sm table-striped table-bordered">
                                            	<p class="lead">Retirement.</p>
                                                <thead style="max-width: 30px;">
                                                    <tr>
                                                        <th scope="col" class="text-center">Date</th>
                                                        <th scope="col" class="text-center">Ret No.</th>
                                                    	<th scope="col" class="text-center">User</th>
                                                        <th scope="col" class="text-center">Activity Name</th>
                                                    	<th scope="col" class="text-center">Supplier</th>
                                                    	<th scope="col" class="text-center">GL Accounts</th>
                                                    	<!-- <th scope="col" class="text-center">Currency</th> -->
                                                    	<th scope="col" class="text-center">VAT</th>
                                                    	<th scope="col" class="text-center">DR</th>
                                                    	<th scope="col" class="text-center">CR</th>
                                                    	<!-- <th scope="col" class="text-center">Balance</th> -->
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                    @foreach($retirements as $ret)
                                                    	<tr>

                                                            <td scope="col" class="text-center">{{$ret->created_at}}</td>
                                                            <td scope="col" class="text-center">{{$ret->ret_no}}</td>
                                                    		<td scope="col" class="text-center"></td>
                                                    		<td scope="col" class="text-center">{{$ret->item_name}}</td>
                                                			<td scope="col" class="text-center">{{$ret->supplier_id}}</td>
                                                    		<td scope="col" class="text-center"></td>
                                                    		<!-- <td scope="col" class="text-center">Tz</td> -->
                                                    		<td scope="col" class="text-center">{{$ret->vat}}</td>
                                                    		<td scope="col" class="text-center">{{$ret->gross_amount}}</td>
                                                    		<td></td>
<!--                                                     		<td></td>
 -->                                                		
                                                    	</tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td scope="col" class="text-center"></td>
                                                            <td></td>
                                                            <td></td>
<!--                                                             <td></td>
 -->                                                        <td scope="col" class="text-center">
                                                                
                                                            </td>
                                                           
                                                        </tr>
                                                @endforeach
                                                        
                                                        
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td scope="col" class="text-center font-weight-bold">Total</td>
                                                            <td scope="col" class="text-center">{{$retirements[0]->vat}}</td>
                                                            <td scope="col" class="text-center">
                                                            <td scope="col" class="text-center"> 
                                                        </tr>
                                                        
                                                </tbody>
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
</div>

@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>

<script type="text/javascript">
	
</script>