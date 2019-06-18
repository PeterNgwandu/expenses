<?php

use App\User;
use App\Department\Department;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Requisitions\RequisitionsController;


?>
@extends('layout.app')

</style>
@section('content')

<div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable" style="margin-top: 30px">
        <div class="card-group mr-5 ml-5">
	        <h3 class="lead bold font-weight-bold">My Dashboard</h3>
        </div>
        <div class="card-group mr-5 ml-5">
            <div class="card p-2 text-center">
                <div class="media align-items-center">
                    <div class="mr-1">
                        <i class="material-icons md-36 text-danger">flag</i>
                    </div>
                    <div class="media-body">
                        <h3 class="mb-0"><strong>{{DashboardController::getAllPendingRequisitionCount(Auth::user()->id)}}</strong></h3>
                        <span>Pending Requisitions</span>
                    </div>
                </div>
            </div>
            <div class="card p-2 text-center">
                <div class="media align-items-center">
                    <div class="mr-3">
                        <i class="material-icons md-36 text-success">flag</i>
                    </div>
                    <div class="media-body">
                        <h3 class="mb-0"><strong>{{DashboardController::getAllRequisitionCount(Auth::user()->id)}}</strong></h3>
                        <span>Total Requisitions</span>
                    </div>
                </div>
            </div>
            <div class="card p-2 text-center">
                <div class="media align-items-center">
                    <div class="mr-3">
                        <i class="material-icons md-36 text-success">flag</i>
                    </div>
                    <div class="media-body">
                        <h3 class="mb-0"><strong>{{DashboardController::getAllRetirementCount(Auth::user()->id)}}</strong></h3>
                        <span>Total Retirements</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-group mt-3 mr-5 ml-5">
            <div class="card p-2 text-center">
                <div class="media align-items-center">
                    <div class="mr-1">
                        <i class="material-icons md-36 text-facebook">flag</i>
                    </div>
                    <div class="media-body">
                        <h3 class="mb-0"><strong>{{DashboardController::getAllExpenseRetirementCount(Auth::user()->id)}}</strong></h3>
                        <span>Expense Retirements</span>
                    </div>
                </div>
            </div>
            <div class="card p-2 text-center">
                <div class="media align-items-center">
                    <div class="mr-3">
                        <i class="material-icons md-36 text-twitter">flag</i>
                    </div>
                    <div class="media-body">
                        <h3 class="mb-0"><strong>{{DashboardController::getAllPaidRequisitionCount(Auth::user()->id)}}</strong></h3>
                        <span>Paid Requisitions</span>
                    </div>
                </div>
            </div>
            <div class="card p-2 text-center">
                <div class="media align-items-center">
                    <div class="mr-3">
                        <i class="material-icons md-36 text-twitter">flag</i>
                    </div>
                    <div class="media-body">
                        <h3 class="mb-0"><strong>{{DashboardController::getAllRetiredRequisitionCount(Auth::user()->id)}}</strong></h3>
                        <span>Retired Requisition</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-group mt-3 mr-5 ml-5">
            @if (Auth::user()->username == 'Admin')
                <button data-toogle="tooltip" data-placement="top" title="Click Here To Backup Your Database" type="submit" class="btn btn-lg btn-twitter database-backup">Database Backup</button>
                
                <p style="margin-left: 20px;" class="font-weight-bold text-facebook mt-3 mr-5">Backed up file : <a data-toogle="tooltip" data-placement="top" title="Click Here To Download Your Database Backup" href="{{url('/download-backup')}}">Download File Here</a> </p>
            @endif
            
        </div>
    </div>
</div>
@endsection
