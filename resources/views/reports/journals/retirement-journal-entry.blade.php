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

        <div class="container" style="max-width: 98% !important;">


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
                                <p class="lead font-weight-bold">Retirements Journal
                                    <span class="float-right mb-2">
                                        <a style="border-radius:0 !important; margin-left: 10px !important;" target="__blank" class="btn btn-sm btn-primary float-right ml-5 mt-2" href="{{url('journal/retirement-report/'.$journal_no)}}">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons  md-2 align-middle">print</i>
                                            </span>
                                            Print
                                        </a>
                                        <a style="border-radius:0 !important; margin-left: 10px !important; background: #218452;" target="__blank" class="btn btn-sm text-white float-right ml-5 mt-2" href="{{route('export-retirement-journal',$journal_no)}}">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons  md-2 align-middle">widgets</i>
                                            </span>
                                            Export Excel
                                        </a>
                                        
                                    </span>
                                </p>
                                <div class="tab-content">
                                    
                                    <div class="" id="contact5" role="tabpanel">
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
                                                        <!-- <th scope="col" class="text-center">VAT Amount</th> -->
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
                                                    <td scope="col" class="text-left">{{$ret->item_name}}</td>
                                                    <td scope="col" class="text-left">{{$ret->supplier_id}}</td>
                                                    <td scope="col" class="text-left">
                                                        {{$ret->Account_Name}}

                                                    </td>
                                                    <td scope="col" class="text-left"></td>
                                                    @if($ret->vat == 'VAT Inclusive')
                                                    <td scope="col" class="text-right">
                                                        {{number_format($ret->gross_amount/1.18,2)}}
                                                    </td>
                                                    @elseif($ret->vat == 'VAT Exclusive')
                                                    <td scope="col" class="text-right">
                                                        {{number_format($ret->gross_amount - $ret->vat_amount,2)}}
                                                    </td>
                                                    @else
                                                    <td scope="col" class="text-right">
                                                        {{number_format($ret->gross_amount,2)}}
                                                    </td>
                                                    @endif
                                                    <td></td> 
                                                     
                                                </tr>
                                                @if($ret->vat != 'Non VAT')
                                                <tr>
                                                    <td scope="col" class="text-center">{{$ret->created_at}}</td>
                                                    <td scope="col" class="text-center">{{$ret->ret_no}}</td>
                                                    <td scope="col" class="text-center">{{$ret->req_no}}</td>
                                                    <td scope="col" class="text-left">{{$ret->item_name}}</td>
                                                    <td scope="col" class="text-left">{{$ret->supplier_id}}</td>
                                                    <td scope="col" class="text-left">{{$vat_account}}</td>
                                                    <td scope="col" class="text-left">{{$ret->vat}}</td>
                                                    <td scope="col" class="text-right">{{number_format($ret->vat_amount,2)}}</td>
                                                    <!-- <td></td> -->
                                                    <td></td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td scope="col" class="text-center">{{$ret->created_at}}</td>
                                                    <td scope="col" class="text-center">{{$ret->ret_no}}</td>
                                                    <td scope="col" class="text-center">{{$ret->req_no}}</td>
                                                    <td scope="col" class="text-left">{{$ret->item_name}}</td>
                                                    <td scope="col" class="text-left">{{$ret->supplier_id}}</td>
                                                    <td scope="col" class="text-left">{{$ret->Account_No}}</td>
                                                    <td></td>
                                                    <td scope="col" class="text-center"></td>
                                                    <td scope="col" class="text-right">                             {{number_format($ret->gross_amount,2)}}
                                                    </td>
                                                    <!-- <td></td> -->
                                                </tr>
                                                @endforeach
                                                        
                                                        
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <!-- <td></td> -->
                                                            <td scope="col" class="text-center"></td>
                                                            <td scope="col" class="text-center font-weight-bold">Total</td>
                                                            <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofRetiredRequisitionsInJournals($journal_no),2)}}</td>
                                                            <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofRetiredRequisitionsInJournals($journal_no),2)}}</td> 
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
