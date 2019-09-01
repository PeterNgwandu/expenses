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

        <div class="container" style="max-width: 98% !important;">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Imprest Journal</h4>
                                    <p class="lead float-right" style="color: #35A45A;">
                                        
                                    </p>
                                </div>
                            </div>
                        </div>

                            <div class="card-body">
                                <div class="row mt-3">
                                    <div class="col-lg-12 mr-3">
                                        <table class="table table-sm table-bordered" style="border: none;">
                                            <p class="lead font-weight-bold mt-2">Imprest Journal: &nbsp;
                                                <span>{{JournalController::generateJournalNo()}}</span>
                                               <!--  <span class="float-right mb-2">
                                                    <a style="border-radius:0 !important; margin-left: 10px !important;" target="__blank" class="btn btn-sm btn-primary print-budget-report float-right ml-5 mt-2" href="">
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
                                                    </a>
                                                    
                                                </span> -->
                                            </p>
                                            
                                            <thead style="max-width: 30px;">
                                                <tr>
                                                    <th scope="col" class="text-center">Date</th>
                                                    <th scope="col" class="text-center">Req #</th>
                                                    <th scope="col" class="text-center">User</th>
                                                    {{-- <th scope="col" class="text-center">Activity Name</th> --}}
                                                    <th scope="col" class="text-center">GL Accounts</th>
                                                    <th scope="col" class="text-center">DR</th>
                                                    <th scope="col" class="text-center">CR</th>
                                                    <th scope="col" class="text-center">Change Bank Account</th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                
                                                @foreach($requisitions as $req)
                                                    
                                                    <tr>                                                    
                                                        <td scope="col" class="text-center">{{$req->created_at}}</td>
                                                        <td scope="col" class="text-center">{{$req->req_no}}</td>
                                                        <td scope="col" class="text-left">{{$req->username}}</td>
                                                    
                                                        <td scope="col" class="text-left">{{$req->account_no}}</td>
                                                        <td scope="col" class="text-right">{{number_format($req->amount_paid,2)}}</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td scope="col" class="text-center">{{$req->created_at}}</td>
                                                    <td scope="col" class="text-center">{{$req->req_no}}</td>
                                                    <td scope="col" class="text-left">{{$req->username}}</td>
                                                        
                                                        <td scope="col" class="text-left">
                                                            {{$req->account}}
                                                            <!-- &nbsp;&nbsp;&nbsp;&nbsp;
                                                            <select name="account_id" req-no="{{$req->req_no}}" class="text-dark font-weight-bold float-right change-account" style="border: none; background: none;">
                                                                
                                                                @foreach($bank_account as $bank)
                                                                    <option value="{{$bank->id}}">{{$bank->account_name}}</option>
                                                                @endforeach
                                                            </select>   -->
                                                                                        
                                                        </td>                                                            
                                                        <td scope="col" class="text-center"></td>
                                                        <td scope="col" class="text-right">{{number_format($req->amount_paid,2)}}</td>

                                                        <td scope="col" class="text-center">
                                                            <select req-no="{{$req->req_no}}" name="account_id" class="form-control change-imprest-accounts btn btn-sm" style="border: none;height: 23px; width: 80px;">
                                                                <option>Change Account</option>
                                                                @for($i = 0; $i < count($bank_account); $i++)
                                                                    <option value="{{$bank_account[$i]->id}}">{{$bank_account[$i]->account_name}}</option>
                                                                @endfor
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    
                                                @endforeach
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        {{-- <td></td> --}}
                                                        <td scope="col" class="text-center font-weight-bold">Total</td>
                                                        <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofPaidRequisitions(),2)}}</td>
                                                        <td scope="col" class="text-right font-weight-bold">{{number_format(JournalController::getTotalofPaidRequisitions(),2)}}</td>
                                                        <td></td>

                                                    </tr>
                                            </tbody>

                                        </table>
                                        <form action="{{url('journal/report')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="journal_no" value="{{JournalController::generateJournalNo()}}">

                                            <button type="submit" style="border-radius:0 !important; margin-left: 10px !important; target="__blank" class="btn btn-sm btn-secondary text-white float-right ml-5 mt-2" href="">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons  md-2 align-middle">send</i>
                                            </span>
                                            Post Imprest Journal
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


 