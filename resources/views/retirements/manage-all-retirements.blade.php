<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Retirements\RetirementController;
use App\Http\Controllers\Requisitions\RequisitionsController;


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
                                    <h4 class="card-title">Submitted Retirements
                                        <span class="float-right">
                                            <p class="lead" style="color: #35A45A;">Retired Requisitions
                                                <span>
                                                   <i class="material-icons md-10 align-middle mb-1 text-success">done</i>
                                                </span>
                                            </p>
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="py-4">
                                    <p class="text-twitter ml-4">Filter by Date</p>
                                    <form action="{{ route('retirement_submitted_filter_by_date') }}" method="POST" class="form-inline ml-4">
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
                                  <div class="table-responsive">
                                      <table id="data-table" class="table table-sm table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Retiree</th>
                                            <th scope="col" class="text-center">Department</th>
                                            <th scope="col" class="text-center">Requisition No.</th>
                                            <th scope="col" class="text-center">Activity Name</th>
                                            <th scope="col" class="text-center">Amount Retired</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$retirements->isEmpty())
                                        @foreach($retirements as $retirement)
                                            <tr>
                                                <td scope="col" class="text-center">{{$retirement->username}}</td>
                                                <td scope="col" class="text-center">{{$retirement->department}}</td>
                                                <td scope="col" class="text-center">{{$retirement->req_no}}</td>
                                                <td scope="col" class="text-left">{{$retirement->activity_name}}</td>
                                                <td scope="col" class="text-right">
                                                    {{number_format(RetirementController::getTotalOfRetiredLines($retirement->req_no),2)}}
                                                </td>
                                                <td scope="col" class="text-center">
                                                    <a href="{{route('requisition.retirements',$retirement->req_no)}}" class="btn btn-sm btn-twitter">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <p class="text-danger">
                                                <span>
                                                   <i class="material-icons md-10 align-middle mb-1 text-danger">do_not_disturb</i>
                                                </span>
                                            No retirements for you</p>
                                        @endif
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
