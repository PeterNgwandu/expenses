<?php

use App\User;
use App\Department\Department;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Requisitions\RequisitionsController;


?>
@extends('layout.app')
<style>
.blink-div {
   animation: flash 2s ease infinite;
}
</style>
@section('content')

<div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable" style="margin-top: 30px">
		<h3 class="lead bold" style="margin-left: 78px;">My Dashboard</h3>
    	<div class="card-deck ml-5 mr-5">
            <div class="card p-2 pl-3 pr-3">

                <div class="media justify-items-center align-items-center h-md-100">
                    <i class="material-icons md-48  text-danger">folder</i>
                    <div class="media-body pl-2">
                        <h3 class="mb-0 text">{{DashboardController::getAllPendingRequisitionCount(Auth::user()->id)}}</h3>
                        <span>Pending Requisitions</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card p-2 pl-3 pr-3">

                <div class="media justify-items-center align-items-center h-md-100">
                    <i class="material-icons md-48 text-link-color">folder</i>
                    <div class="media-body pl-2">
                        <h3 class="mb-0 text">{{DashboardController::getAllRequisitionCount(Auth::user()->id)}}</h3>
                        <span>Total Requisitions</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card p-2 pl-3 pr-3">

                <div class="media justify-items-center align-items-center h-md-100">
                    <i class="material-icons md-48 text-link-color">folder</i>
                    <div class="media-body pl-2">
                        <h3 class="mb-0 text">{{DashboardController::getAllRetirementCount(Auth::user()->id)}}</h3>
                        <span>Total Retirements</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card p-2 pl-3 pr-3">
                <div class="media justify-items-center align-items-center h-md-100">
                    <i class="material-icons text-link-color md-48">folder</i>
                    <div class="media-body pl-2">
                        <h4 class="m-0">{{DashboardController::getAllExpenseRetirementCount(Auth::user()->id)}}</h4>
                        <span>Expense Retirements</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card p-2 pl-3 pr-3">
                <div class="media justify-items-center align-items-center h-md-100">
                    <i class="material-icons text-success md-48">check_circle</i>
                    <div class="media-body pl-2">
                        <h4 class="m-0">{{DashboardController::getAllPaidRequisitionCount(Auth::user()->id)}}</h4>
                        <span>Paid Requisitions</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="card p-2 pl-3 pr-3">
                <div class="media justify-items-center align-items-center h-md-100">
                    <i class="material-icons text-success md-48">check_circle</i>
                    <div class="media-body pl-2">
                        <h4 class="m-0">{{DashboardController::getAllRetiredRequisitionCount(Auth::user()->id)}}</h4>
                        <span>Retired Requisitions</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            @if (Auth::user()->username == 'Admin')
                <button type="submit" class="btn btn-lg btn-twitter database-backup">Database Backup</button>
            @endif
            <?php
                use Illuminate\Support\Facades\Storage;
            ?>

        </div>
    </div>
</div>
@endsection
