<?php

use Illuminate\Support\Carbon;
use App\Http\Controllers\Retirements\RetirementController;
use App\Http\Controllers\Requisitions\RequisitionsController;

 ?>
@extends('layout.app')
<style type="text/css">
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
                                <div class="col-lg-6">
                                    <h4 class="card-title">Submitted Requisitions</h4>
                                </div>
                            </div>
                        </div>
                        <br>
                            <table id="data-table" class="table table-sm table-striped table-dark mb-0">
                                    @if(!$paid_requisitions->isEmpty())
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Requester</th>
                                            <th scope="col" class="text-center">Department</th>
                                            <th scope="col" class="text-center">Requisition No.</th>
                                            <th scope="col" class="text-center">Activity Name</th>
                                            <th scope="col" class="text-center">Totals</th>
                                            <th scope="col" class="text-center">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paid_requisitions as $requisition)
                                            <tr>
                                                <td scope="col" class="text-center">{{$requisition->username}}</td>
                                                <td scope="col" class="text-center">{{$requisition->department}}</td>
                                                <td scope="col" class="text-center">{{$requisition->req_no}}</td>
                                                <td scope="col" class="text-left">{{$requisition->activity_name}}</td>
                                                <td scope="col" class="text-success text-center font-weight-bold">
                                                    {{ number_format(RequisitionsController::getRequisitionTotal($requisition->req_no)) }}
                                                </td>
                                                <td scope="col" class="text-center">
                                                    <a href="{{route('paid-requisition',$requisition->req_no)}}" class="btn btn-sm btn-outline-success">View All Requisitions</a>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @endif
                                    {{-- @if($paid_requisitions->isEmpty())
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Requester</th>
                                            <th scope="col" class="text-center">Department</th>
                                            <th scope="col" class="text-center">Requisition No.</th>
                                            <th scope="col" class="text-center">Totals</th>
                                            <th scope="col" class="text-center">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paid_no_budget_requsition as $paid_no_budget)
                                            <tr>
                                                <td scope="col" class="text-center">{{$paid_no_budget->username}}</td>
                                                <td scope="col" class="text-center">{{$paid_no_budget->department}}</td>
                                                <td scope="col" class="text-center">{{$paid_no_budget->req_no}}</td>
                                                <td scope="col" class="text-success text-center font-weight-bold">
                                                    {{ RequisitionsController::getRequisitionTotal($paid_no_budget->req_no) }} /=
                                                </td>
                                                <td scope="col" class="text-center">
                                                    <a href="{{route('paid-requisition',$paid_no_budget->req_no)}}" class="btn btn-sm btn-outline-success">View All Requisitions</a>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @endif --}}
                            </table>
                        </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('.preload').fadeOut('3000', function() {
            $('.mydata').fadeIn('2000');
        });
    });

</script>
