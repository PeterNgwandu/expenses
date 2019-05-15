<?php
use App\Journal\Journal;
use App\Journal\RetirementsJournal;
use App\Http\Controllers\Journal\JournalController;

?>
@extends('layout.app')

@section('content')
<div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Journals</h4>
                                    <p class="lead float-right" style="color: #35A45A;">
                                        
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="py-4 m-2">
                                <ul class="nav nav-tabs mb-2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#home5" role="tab" aria-controls="home5" aria-selected="true">Imprest Journal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#contact5" role="tab" aria-controls="contact5" aria-selected="false">Retirement Journal</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="home5" role="tabpanel">
                                            <table class="table table-sm table-striped table-bordered mt-4">
                                            	
                                                    <thead style="max-width: 30px;">
                                                        <tr>
                                                            <th scope="col" class="text-center">Date</th>
                                                            <th scope="col" class="text-center">Req #</th>
                                                            <th scope="col" class="text-center">User</th>
                                                            <th scope="col" class="text-center">Activity Name</th>
                                                            <th scope="col" class="text-center">GL Accounts</th>
                                                            <th scope="col" class="text-center">DR</th>
                                                            <th scope="col" class="text-center">CR</th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                            @foreach($journal as $req)
                                                    	
                                                    	<tr>                                                   	
                                                            <td scope="col" class="text-center">{{$req->created_at}}</td>
                                                            <td scope="col" class="text-center">{{$req->req_no}}</td>
                                            				<td scope="col" class="text-center">{{$req->username}}</td>
                                            				
                                                			<td scope="col" class="text-center">{{$req->activity_name}}</td>                                                			
                                                    		<td scope="col" class="text-center">{{$req->account_no}}</td>
                                                    		<td scope="col" class="text-center">{{number_format($req->amount_paid)}}</td>
                                                            <td></td>
                                                    	</tr>
                                                        <tr>
                                                            <td scope="col" class="text-center"></td>
                                                            <td scope="col" class="text-center"></td>
                                                            <td scope="col" class="text-center"></td>
                                                            
                                                            <td scope="col" class="text-center"></td>                                                            
                                                            <td scope="col" class="text-center">{{$req->account}}</td>
                                                            <td scope="col" class="text-center"></td>
                                                            <td scope="col" class="text-center">{{number_format($req->amount_paid)}}</td>
                                                        </tr>

                                                @endforeach
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td scope="col" class="text-center font-weight-bold">Total</td>
                                                            @if(!$journal->isEmpty())
                                                                <td scope="col" class="text-center font-weight-bold">{{number_format(JournalController::getTotalofJournalRequisitions())}}</td>
                                                                <td scope="col" class="text-center font-weight-bold">{{number_format(JournalController::getTotalofJournalRequisitions())}}</td>
                                                            @else
                                                                <td scope="col" class="text-center"></td>
                                                                <td scope="col" class="text-center"></td>
                                                            @endif
                                                        </tr>                                                    
                                                    </tbody>
                                                </table>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="contact5" role="tabpanel">
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead style="max-width: 30px;">
                                                    <tr>
                                                        <th scope="col" class="text-center">Date</th>
                                                        <th scope="col" class="text-center">Ret #.</th>
                                                        <th scope="col" class="text-center">Req #.</th>
                                                        <th scope="col" class="text-center">Item Name</th>
                                                        <th scope="col" class="text-center">Supplier</th>
                                                        <th scope="col" class="text-center">GL Accounts</th>
                                                        <th scope="col" class="text-center">VAT</th>
                                                        <th scope="col" class="text-center">DR</th>
                                                        <th scope="col" class="text-center">CR</th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                        @foreach($retirement_journal as $ret)
                                                    	<tr>

                                                            <td scope="col" class="text-center">{{$ret->created_at}}</td>
                                                            <td scope="col" class="text-center">{{$ret->ret_no}}</td>
                                                            <td scope="col" class="text-center">{{$ret->req_no}}</td>
                                                    		<td scope="col" class="text-center">{{$ret->item_name}}</td>
                                                			<td scope="col" class="text-center">{{$ret->supplier_id}}</td>
                                                            <td scope="col" class="text-center">{{$ret->Account_No}}</td>
                                                    		<td scope="col" class="text-center">{{$ret->vat}}</td>
                                                    		<td scope="col" class="text-center">{{number_format(JournalController::getSumOfRetirement($ret->journal_no))}}</td>
                                                    		<td></td>                                              		
                                                    	</tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        <td scope="col" class="text-center">{{$ret->Account_Name}}</td>
                                                            <td></td>
                                                            <td scope="col" class="text-center"></td>
<!--                                                             <td></td>
 -->                                                        <td scope="col" class="text-center">{{number_format(JournalController::getSumOfRetirement($ret->journal_no))}}</td>
                                                           
                                                        </tr>
                                                @endforeach
                                                        
                                                        
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td scope="col" class="text-center"></td>
                                                            <td scope="col" class="text-center font-weight-bold">Total</td>
                                                            <td scope="col" class="text-center font-weight-bold">{{number_format(JournalController::getTotalofRetiredRequisitionsInJournals($journal_no))}}</td>
                                                            <td scope="col" class="text-center font-weight-bold">{{number_format(JournalController::getTotalofRetiredRequisitionsInJournals($journal_no))}}</td> 
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

@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script type="text/javascript">

</script>
