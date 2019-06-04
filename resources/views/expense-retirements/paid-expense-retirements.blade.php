<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Requisitions\RequisitionsController;
use App\Http\Controllers\ExpenseRetirements\ExpenseRetirementController;

?>

@extends('layout.app')
<style type="text/css">
    #flash {
        position: absolute;
        bottom: 10px;
        right: 20px;
        z-index: 10;
    }
    .mydata {
        display: none;
    }
    .preload {
        margin: 0px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        margin-top: 10px;
        background: #ffffff;
    }
    .img {
        background: #ffffff;
    }
</style>
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
                                    <h4 class="card-title">Paid Expense Retirements
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                                 @if($expense_retirements->isEmpty())
                                    <p class="text-danger">
                                        <span>
                                           <i class="material-icons md-10 align-middle mb-1 text-danger">do_not_disturb</i>
                                        </span>
                                    No expense retirements for you</p>
                                 @endif
                                 <div class="py-4">
                                     <div class="table-responsive">
                                         <table id="data-table" class="table table-striped table-dark table-sm mb-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" class="text-center">Retiree</th>
                                                <th scope="col" class="text-center">Retirement No.</th>
                                                <th scope="col" class="text-center">Gross Amount</th>
                                                <th scope="col" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                               @foreach($expense_retirements as $retirement)
                                                <tr>
                                                    <td scope="col" class="text-center">{{$retirement->username}}</td>
                                                    <td scope="col" class="text-center">{{$retirement->ret_no}}</td>
                                                    <td scope="col" class="text-right">{{ number_format(ExpenseRetirementController::getExpenseRetirementTotal($retirement->ret_no),2)}}</td>
                                                    <td scope="col" class="text-center">
                                                        <a class="btn btn-sm btn-outline-success" href="{{route('expense_retirements.show',$retirement->ret_no)}}">View</a>
                                                    </td>
                                                </tr>

                                               @endforeach
                                        </tbody>
                                    </table>
                                     </div>
                                 </div>

                            </div>
                        </div>
                </div>
            </div>
             @if($flash = session('message'))
                <div id="flash" class="alert alert-info">
                    {{ $flash }}
                </div>
            @endif
        </div>
    </div>
</div>


@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script type="text/javascript">
    $(function() {
       $('#flash').delay(500).fadeIn('normal', function() {
          $(this).delay(2500).fadeOut();
       });
    });
    $(document).ready(function() {
        $('.preload').fadeOut('3000', function() {
            $('.mydata').fadeIn('2000');
        });
    });
</script>
