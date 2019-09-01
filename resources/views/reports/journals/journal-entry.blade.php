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
                                <p class="lead font-weight-bold">Imprests Journal
                                    <span class="float-right mb-2">
                                        <a style="border-radius:0 !important; margin-left: 10px !important;" target="__blank" class="btn btn-sm btn-primary print float-right ml-5 mt-2" href="{{url('/journal/report/'.$journal_no)}}">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons  md-2 align-middle">print</i>
                                            </span>
                                            Print
                                        </a>
                                        <a style="border-radius:0 !important; margin-left: 10px !important; background: #218452;" target="__blank" class="btn btn-sm text-white float-right ml-5 mt-2" href="{{route('export-imprests-journal',$journal_no)}}">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons  md-2 align-middle">widgets</i>
                                            </span>
                                            Export Excel
                                        </a>
                                        
                                    </span>
                                </p>
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
                                            				<td scope="col" class="text-left">{{$req->username}}</td>
                                            				
                                                			<td scope="col" class="text-left">{{$req->activity_name}}</td>                                                			
                                                    		<td scope="col" class="text-left">{{$req->account_no}}</td>
                                                    		<td scope="col" class="text-right">{{number_format($req->amount_paid,2)}}</td>
                                                            <td></td>
                                                    	</tr>
                                                        <tr>
                                                            <td scope="col" class="text-center">{{$req->created_at}}</td>
                                                            <td scope="col" class="text-center">{{$req->req_no}}</td>
                                                            <td scope="col" class="text-left">{{$req->username}}</td>
                                                            
                                                            <td scope="col" class="text-left">{{$req->activity_name}}</td>                                                            
                                                            <td scope="col" class="text-left">{{$req->account}}</td>
                                                            <td scope="col" class="text-center"></td>
                                                            <td scope="col" class="text-right">{{number_format($req->amount_paid,2)}}</td>
                                                        </tr>

                                                @endforeach
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td scope="col" class="text-right font-weight-bold">Total</td>
                                                            @if(!$journal->isEmpty())
                                                                <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofJournalRequisition($journal_no),2)}}</td>
                                                                <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofJournalRequisition($journal_no),2)}}</td>
                                                            @else
                                                                <td scope="col" class="text-center"></td>
                                                                <td scope="col" class="text-center"></td>
                                                            @endif
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
