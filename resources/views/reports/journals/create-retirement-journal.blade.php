<?php
use App\Accounts\Account;
use Illuminate\Support\Carbon;
use App\Retirements\Retirement;
use App\Http\Controllers\Journal\JournalController;

?>

@extends('layout.app')

@section('content')
<div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container" style="max-width: 100% !important;">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Retirement Journals</h4>
                                    <p class="lead float-right" style="color: #35A45A;">
                                        
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-lg-12 mr-3">
                                    <table class="table table-sm table-bordered">
                                        <p class="lead font-weight-bold mt-3">Retirements Journal: &nbsp;
                                            <span>{{JournalController::generateRetirementJournalNo()}}</span>
                                            <span class="float-right mb-2">
                                                <!-- <a style="border-radius:0 !important; margin-left: 10px !important;" target="__blank" class="btn btn-sm btn-primary print-budget-report float-right ml-5 mt-2" href="">
                                                    <span>
                                                        <i style="cursor: pointer;" class="material-icons  md-2 align-middle">print</i>
                                                    </span>
                                                    Print
                                                </a>
                                                <a style="border-radius:0 !important; margin-left: 10px !important; background: #218452;" target="__blank" class="btn btn-sm text-white float-right ml-5 mt-2" href="">
                                                    <span>
                                                        <i style="cursor: pointer;" class="material-icons  md-2 align-middle">widgets</i>
                                                    </span>
                                                    Export Excel
                                                </a> -->
                                                
                                            </span>
                                        </p>
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
                                                <th scope="col" class="text-center">Edit Expense Account</th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                            @foreach($retirements as $ret)
                                                <tr>

                                                    <td scope="col" class="text-center">{{$ret->created_at}}</td>
                                                    <td scope="col" class="text-center">{{$ret->ret_no}}</td>
                                                    <td scope="col" class="text-center">{{$ret->req_no}}</td>
                                                    <td scope="col" class="text-left">{{$ret->item_name}}</td>
                                                    <td scope="col" class="text-left">{{$ret->supplier_id}}</td>
                                                    <td scope="col" class="text-left">
                                                        {{$ret->Account_Name}}<br>

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
                                                    <td scope="col" style="width: 10px;" class="text-center">
                                                        <select ret-no="{{$ret->ret_no}}" name="account_id" class="form-control change-retirement-accounts btn btn-sm" style="border: none;height: 23px; width: 80px;">
                                                            <option>Select</option>
                                                            @for($i = 0; $i < count($expense_account); $i++)
                                                                <option value="{{$expense_account[$i]->id}}">{{$expense_account[$i]->account_name}}</option>
                                                            @endfor
                                                        </select>
                                                    </td> 
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
                                                    <td></td>
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
                                                    <td></td>
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
                                                    <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofRetiredRequisitions(),2)}}</td>
                                                    <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofRetiredRequisitions(),2)}}</td> 
                                                    <td></td>
                                                </tr>
                                                
                                        </tbody>
                                    </table>
                                    <form action="{{url('retirement-journal/report')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="journal_no" value="{{JournalController::generateRetirementJournalNo()}}">

                                        <button type="submit" style="border-radius:0 !important; margin-left: 10px !important; target="__blank" class="btn btn-sm btn-secondary text-white float-right ml-5 mt-2" href="">
                                        <span>
                                            <i style="cursor: pointer;" class="material-icons  md-2 align-middle">send</i>
                                        </span>
                                        Post Retirement Journal
                                        </button>

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
</div>

@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>

<script type="text/javascript">
	
</script>


 