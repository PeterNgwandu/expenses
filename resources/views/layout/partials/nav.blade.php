<?php
use App\StaffLevel\StaffLevel;
$stafflevels = StaffLevel::all();

$hod = $stafflevels[0]->id;
$ceo = $stafflevels[1]->id;
$supervisor = $stafflevels[2]->id;
$normalStaff = $stafflevels[3]->id;
$financeDirector = $stafflevels[4]->id;

?>
<nav class="navbar navbar-expand-md navbar-dark bg-primary-dark d-print-none" style="width: 100%">
<div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
        <!-- SVG Logo -->

        <svg width="24px" height="24px" viewBox="0 0 23 23" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMaxYMin meet">
<defs></defs>
<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
  <path d="M3,-3.15936513e-15 L20,-2.74650094e-15 L20,-2.66453526e-15 C21.6568542,-2.96889791e-15 23,1.34314575 23,3 L23,20 L23,20 C23,21.6568542 21.6568542,23 20,23 L6,23 L3,23 L3,23 C1.34314575,23 1.09108455e-15,21.6568542 8.8817842e-16,20 L0,3 L0,3 C-2.02906125e-16,1.34314575 1.34314575,-3.24835102e-15 3,-3.55271368e-15 Z" id="logoBackground"></path>
  <rect id="Rectangle-6" fill="#FFFFFF" x="1.0952381" y="12.0960631" width="9.96553315" height="9.76943696"></rect>
  <rect id="Rectangle-6-Copy" fill="#FFFFFF" x="11.9533428" y="1.05597629" width="9.96553315" height="9.76943696"></rect>
</g>
</svg>
        <!-- //End SVG Logo -->

        <span class="navbar-brand-text">Expenses</span>
    </a>
    <!-- DEMO COLORS -->
    <div class="d-none d-md-block d-lg-block">

    </div>
    <!-- END DEMO  COLORS -->
    <button class="navbar-toggler navbar-toggler-right d-md-none d-lg-none" type="button" data-toggle="sidebar">
<span class="navbar-toggler-icon"></span>
</button>


    <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav align-items-center flex-auto">
            @if(Auth::user()->username == 'Admin')
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Department</a>
                <div class="dropdown-menu">
                    <a href="{{ route('departments.index') }}" class="dropdown-item dropdown-item-action active">Register Department</a>
                    <a href="{{ url('/view-departments') }}" class="dropdown-item dropdown-item-action">View Departments</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Staff Management</a>
                <div class="dropdown-menu">
                    <a href="{{ route('register-levels') }}" class="dropdown-item dropdown-item-action active">Register Staff Levels</a>
                    <a href="{{ route('staffs.create') }}" class="dropdown-item dropdown-item-action ">Add Staff</a>
                    <a href="{{ url('registered-staffs') }}" class="dropdown-item dropdown-item-action">View Staffs </a>
                    <a href="{{ route('staff-levels.index') }}" class="dropdown-item dropdown-item-action">View Staffs Levels</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Budget</a>
                <div class="dropdown-menu">
                    <a href="{{ route('budgets.create') }}" class="dropdown-item dropdown-item-action active">Create Budgets</a>
                    <a href="{{ route('budgets.index') }}" class="dropdown-item dropdown-item-action">View Budgets</a>
                    <a href="{{ route('budgets.rejected') }}" class="dropdown-item dropdown-item-action">Rejected Budgets</a>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('accounts.index') }}" class="nav-link" aria-expanded="false">Account</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('limits.create') }}" class="nav-link" aria-expanded="false">Approval Limit</a>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Requisition</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition(s)</a> -->
                    <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition</a>
                    <a href="{{ route('pending-requisitions') }}" class="dropdown-item dropdown-item-action">Pending Requisitions</a>
                    <a href="{{ route('submitted-requisitions') }}" class="dropdown-item dropdown-item-action">Submitted Requisitions</a>
                    <a href="{{ route('approved-requisitions') }}" class="dropdown-item dropdown-item-action">Approved Requisitions</a>
                    <a href="{{ route('paid-requisitions') }}" class="dropdown-item dropdown-item-action">Paid Requisitions</a>
                    {{-- <a href="{{ route('retired-requisitions') }}" class="dropdown-item dropdown-item-action">Retired Requisitions</a> --}}
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Retirement</a>
                <div class="dropdown-menu">
                    <a href="{{ route('retirements.create') }}" class="dropdown-item dropdown-item-action active">Retire Requisition</a>
                    <a href="{{ route('retirements.index') }}" class="dropdown-item dropdown-item-action">Pending Retirements</a>
                    <a href="{{ route('retirements.submitted') }}" class="dropdown-item dropdown-item-action">Submitted Retirements</a>
                    <a href="{{ route('retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Retirements</a>
                    <a href="{{route('expense-retirements.create')}}" class="dropdown-item dropdown-item-action">Create Expense Retirements</a>
                    <a href="{{ route('expense-retirements.pending') }}" class="dropdown-item dropdown-item-action">Pending Expense Retirements</a>
                    <a href="{{ route('expense-retirements.index') }}" class="dropdown-item dropdown-item-action">Submitted Expense Retirements</a>
                    <a href="{{ route('expense-retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Expense Retirements</a>
                    <a href="{{ route('expense-retirements.paid') }}" class="dropdown-item dropdown-item-action">Paid Expense Retirements</a>

                </div>
            </li>

            {{-- <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Journal</a>
                <div class="dropdown-menu">
                    <a href="{{ route('journals.create') }}" class="dropdown-item dropdown-item-action active">Create Journals</a>
                    <a href="#" class="dropdown-item dropdown-item-action">View Journals</a>
                </div>
            </li> --}}

            @elseif(Auth::user()->stafflevel_id == $financeDirector)
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Department</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('departments.index') }}" class="dropdown-item dropdown-item-action active">Register Department</a> -->
                    <a href="{{ url('/view-departments') }}" class="dropdown-item dropdown-item-action">View Departments</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Staff Management</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('register-levels') }}" class="dropdown-item dropdown-item-action active">Register Staff Levels</a> -->
                    <!-- <a href="{{ route('staffs.create') }}" class="dropdown-item dropdown-item-action ">Add Staff</a> -->
                    <a href="{{ url('registered-staffs') }}" class="dropdown-item dropdown-item-action">View Staffs </a>
                    <a href="{{ route('staff-levels.index') }}" class="dropdown-item dropdown-item-action">View Staffs Levels</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Budget</a>
                <div class="dropdown-menu">
                    <a href="{{ route('budgets.create') }}" class="dropdown-item dropdown-item-action active">Create Budgets</a>
                    <a href="{{ route('budgets.index') }}" class="dropdown-item dropdown-item-action">View Budgets</a>
                    <a href="{{ route('budgets.rejected') }}" class="dropdown-item dropdown-item-action">Rejected Budgets</a>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('accounts.index') }}" class="nav-link" aria-expanded="false">Account</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('limits.create') }}" class="nav-link" aria-expanded="false">Approval Limit</a>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Requisition</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition(s)</a> -->
                    <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition</a>
                    <a href="{{ route('pending-requisitions') }}" class="dropdown-item dropdown-item-action">Pending Requisitions</a>
                    <a href="{{ route('submitted-requisitions') }}" class="dropdown-item dropdown-item-action">Submitted Requisitions</a>
                    <a href="{{ route('approved-requisitions') }}" class="dropdown-item dropdown-item-action">Approved Requisitions</a>
                    <a href="{{ route('paid-requisitions') }}" class="dropdown-item dropdown-item-action">Paid Requisitions</a>
                    {{-- <a href="{{ route('retired-requisitions') }}" class="dropdown-item dropdown-item-action">Retired Requisitions</a> --}}
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Retirement</a>
                <div class="dropdown-menu">
                    <a href="{{ route('retirements.create') }}" class="dropdown-item dropdown-item-action active">Retire Requisition</a>
                    <a href="{{ route('retirements.index') }}" class="dropdown-item dropdown-item-action">Pending Retirements</a>
                    <a href="{{ route('retirements.submitted') }}" class="dropdown-item dropdown-item-action">Submitted Retirements</a>
                    <a href="{{ route('retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Retirements</a>
                    <a href="{{ route('retirements.manage-all') }}" class="dropdown-item dropdown-item-action">Manage Retirements</a>
                    <a href="{{route('expense-retirements.create')}}" class="dropdown-item dropdown-item-action">Create Expense Retirements</a>
                    <a href="{{ route('expense-retirements.pending') }}" class="dropdown-item dropdown-item-action">Pending Expense Retirements</a>
                    <a href="{{ route('expense-retirements.index') }}" class="dropdown-item dropdown-item-action">Submitted Expense Retirements</a>
                    <a href="{{ route('expense-retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Expense Retirements</a>
                    <a href="{{ route('expense-retirements.paid') }}" class="dropdown-item dropdown-item-action">Paid Expense Retirements</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Reports</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('journals.create') }}" class="dropdown-item dropdown-item-action">Journals Reports</a>
                    <a href="{{ route('journals.view') }}" class="dropdown-item dropdown-item-action">View Imprest Journals</a>
                    <a href="{{ route('retirement-journals.view') }}" class="dropdown-item dropdown-item-action">View Retirement Journals</a>
                    <a href="{{ route('expense-retirement-journals.view') }}" class="dropdown-item dropdown-item-action">View Expense Retirement Journals</a> -->
                    <a href="{{ route('budgets-report') }}" class="dropdown-item dropdown-item-action">Budget Reports</a>
                    <a href="{{ route('unretired-imprests') }}" class="dropdown-item dropdown-item-action">Unretired Imprest Reports</a>  

                    <a href="{{ route('refunds-received') }}" class="dropdown-item dropdown-item-action">Refunds Received Reports</a>

                   <!--  <a href="{{ route('unretired-imprests') }}" class="dropdown-item dropdown-item-action">View Refunds Received </a>  -->          
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Journals</a>
                <div class="dropdown-menu">
                    <a href="{{ route('journals.create') }}" class="dropdown-item dropdown-item-action">Create Imprest Journals</a>
                    <a href="{{ route('create-retirements-journal') }}" class="dropdown-item dropdown-item-action">Create Retirement Journals</a>
                    <a style="border: none;" href="{{ route('create-expense-retirements-journal') }}" class="dropdown-item dropdown-item-action">Create Expense Retirement Journals</a> 
                    ..............................................................................
                    <a href="{{ route('journals.view') }}" class="dropdown-item dropdown-item-action">View Imprest Journals</a>
                    <a href="{{ route('retirement-journals.view') }}" class="dropdown-item dropdown-item-action">View Retirement Journals</a>
                    <a href="{{ route('expense-retirement-journals.view') }}" class="dropdown-item dropdown-item-action">View Expense Retirement Journals</a>  

                </div>             
            </li>

            @elseif(Auth::user()->stafflevel_id == $ceo)
            <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Department</a>
                    <div class="dropdown-menu">
                        <!-- <a href="{{ route('departments.index') }}" class="dropdown-item dropdown-item-action active">Register Department</a> -->
                        <a href="{{ url('/view-departments') }}" class="dropdown-item dropdown-item-action">View Departments</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Staff Management</a>
                    <div class="dropdown-menu">
                        <!-- <a href="{{ route('register-levels') }}" class="dropdown-item dropdown-item-action active">Register Staff Levels</a> -->
                        <!-- <a href="{{ route('staffs.create') }}" class="dropdown-item dropdown-item-action ">Add Staff</a> -->
                        <a href="{{ url('registered-staffs') }}" class="dropdown-item dropdown-item-action">View Staffs </a>
                        <a href="{{ route('staff-levels.index') }}" class="dropdown-item dropdown-item-action">View Staffs Levels</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Budget</a>
                    <div class="dropdown-menu">
                        <a href="{{ route('budgets.create') }}" class="dropdown-item dropdown-item-action active">Create Budgets</a>
                        <a href="{{ route('budgets.index') }}" class="dropdown-item dropdown-item-action">View Budgets</a>
                        <a href="{{ route('budgets.rejected') }}" class="dropdown-item dropdown-item-action">Rejected Budgets</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('accounts.index') }}" class="nav-link" aria-expanded="false">Account</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('limits.create') }}" class="nav-link" aria-expanded="false">Approval Limit</a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Requisition</a>
                    <div class="dropdown-menu">
                        <!-- <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition(s)</a> -->
                        <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition</a>
                        <a href="{{ route('pending-requisitions') }}" class="dropdown-item dropdown-item-action">Pending Requisitions</a>
                        <a href="{{ route('submitted-requisitions') }}" class="dropdown-item dropdown-item-action">Submitted Requisitions</a>
                        <a href="{{ route('approved-requisitions') }}" class="dropdown-item dropdown-item-action">Approved Requisitions</a>
                        <a href="{{ route('paid-requisitions') }}" class="dropdown-item dropdown-item-action">Paid Requisitions</a>
                        {{-- <a href="{{ route('retired-requisitions') }}" class="dropdown-item dropdown-item-action">Retired Requisitions</a> --}}
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Retirement</a>
                    <div class="dropdown-menu">
                        <a href="{{ route('retirements.create') }}" class="dropdown-item dropdown-item-action active">Retire Requisition</a>
                        <a href="{{ route('retirements.index') }}" class="dropdown-item dropdown-item-action">Pending Retirements</a>
                        <a href="{{ route('retirements.submitted') }}" class="dropdown-item dropdown-item-action">Submitted Retirements</a>
                        <a href="{{ route('retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Retirements</a>
                        <a href="{{route('expense-retirements.create')}}" class="dropdown-item dropdown-item-action">Create Expense Retirements</a>
                        <a href="{{ route('expense-retirements.pending') }}" class="dropdown-item dropdown-item-action">Pending Expense Retirements</a>
                        <a href="{{ route('expense-retirements.index') }}" class="dropdown-item dropdown-item-action">Submitted Expense Retirements</a>
                        <a href="{{ route('expense-retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Expense Retirements</a>
                        <a href="{{ route('expense-retirements.paid') }}" class="dropdown-item dropdown-item-action">Paid Expense Retirements</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Reports</a>
                    <div class="dropdown-menu">
                        <!-- <a href="{{ route('journals.create') }}" class="dropdown-item dropdown-item-action">Journals Reports</a>
                        <a href="{{ route('journals.view') }}" class="dropdown-item dropdown-item-action">View Imprest Journals</a>
                        <a href="{{ route('retirement-journals.view') }}" class="dropdown-item dropdown-item-action">View Retirement Journals</a>
                        <a href="{{ route('expense-retirement-journals.view') }}" class="dropdown-item dropdown-item-action">View Expense Retirement Journals</a> -->
                        <a href="{{ route('budgets-report') }}" class="dropdown-item dropdown-item-action">Budget Reports</a>
                        <a href="{{ route('unretired-imprests') }}" class="dropdown-item dropdown-item-action">Unretired Imprest Reports</a>  

                        <a href="{{ route('refunds-received') }}" class="dropdown-item dropdown-item-action">Refunds Received Reports</a>

                       <!--  <a href="{{ route('unretired-imprests') }}" class="dropdown-item dropdown-item-action">View Refunds Received </a>  -->          
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Journal</a>
                    <div class="dropdown-menu">
                        {{-- <a href="{{ route('journals.create') }}" class="dropdown-item dropdown-item-action active">Create Journals</a> --}}
                        <a href="{{ route('journals.view') }}" class="dropdown-item dropdown-item-action">View Journals</a>
                    </div>
                </li>
            @elseif(Auth::user()->stafflevel_id == $hod)
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Department</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('departments.index') }}" class="dropdown-item dropdown-item-action active">Register Department</a> -->
                    <a href="{{ url('/view-departments') }}" class="dropdown-item dropdown-item-action">View Departments</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Staff Management</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('register-levels') }}" class="dropdown-item dropdown-item-action active">Register Staff Levels</a> -->
                    <!-- <a href="{{ route('staffs.create') }}" class="dropdown-item dropdown-item-action ">Add Staff</a> -->
                    <a href="{{ url('registered-staffs') }}" class="dropdown-item dropdown-item-action">View Staffs </a>
                    <a href="{{ route('staff-levels.index') }}" class="dropdown-item dropdown-item-action">View Staffs Levels</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Budget</a>
                <div class="dropdown-menu">
                    <a href="{{ route('budgets.create') }}" class="dropdown-item dropdown-item-action active">Create Budgets</a>
                    <a href="{{ route('budgets.index') }}" class="dropdown-item dropdown-item-action">View Budgets</a>
                    <a href="{{ route('budgets.rejected') }}" class="dropdown-item dropdown-item-action">Rejected Budgets</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Requisition</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition(s)</a> -->
                    <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition</a>
                    <a href="{{ route('pending-requisitions') }}" class="dropdown-item dropdown-item-action">Pending Requisitions</a>
                    <a href="{{ route('submitted-requisitions') }}" class="dropdown-item dropdown-item-action">Submitted Requisitions</a>
                    <a href="{{ route('approved-requisitions') }}" class="dropdown-item dropdown-item-action">Approved Requisitions</a>
                    <a href="{{ route('paid-requisitions') }}" class="dropdown-item dropdown-item-action">Paid Requisitions</a>
                    {{-- <a href="{{ route('retired-requisitions') }}" class="dropdown-item dropdown-item-action">Retired Requisitions</a> --}}
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Retirement</a>
                <div class="dropdown-menu">
                    <a href="{{ route('retirements.create') }}" class="dropdown-item dropdown-item-action active">Retire Requisition</a>
                    <a href="{{ route('retirements.index') }}" class="dropdown-item dropdown-item-action">Pending Retirements</a>
                    <a href="{{ route('retirements.submitted') }}" class="dropdown-item dropdown-item-action">Submitted Retirements</a>
                    <a href="{{ route('retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Retirements</a>
                    <a href="{{route('expense-retirements.create')}}" class="dropdown-item dropdown-item-action">Create Expense Retirements</a>
                    <a href="{{ route('expense-retirements.pending') }}" class="dropdown-item dropdown-item-action">Pending Expense Retirements</a>
                    <a href="{{ route('expense-retirements.index') }}" class="dropdown-item dropdown-item-action">Submitted Expense Retirements</a>
                    <a href="{{ route('expense-retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Expense Retirements</a>
                    <a href="{{ route('expense-retirements.paid') }}" class="dropdown-item dropdown-item-action">Paid Expense Retirements</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Reports</a>
                <div class="dropdown-menu">
 
                    <a href="{{ route('budgets-report') }}" class="dropdown-item dropdown-item-action">Budget Reports</a>
                    <!-- <a href="{{ route('unretired-imprests') }}" class="dropdown-item dropdown-item-action">Unretired Imprest Reports</a>  

                    <a href="{{ route('refunds-received') }}" class="dropdown-item dropdown-item-action">Refunds Received Reports</a> -->
          
                </div>
            </li>

            @elseif(Auth::user()->stafflevel_id == $supervisor)
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Budget</a>
                <div class="dropdown-menu">
                    <a href="{{ route('budgets.create') }}" class="dropdown-item dropdown-item-action active">Create Budgets</a>
                    <a href="{{ route('budgets.index') }}" class="dropdown-item dropdown-item-action">View Budgets</a>
                    <a href="{{ route('budgets.rejected') }}" class="dropdown-item dropdown-item-action">Rejected Budgets</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Requisition</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition(s)</a> -->
                    <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition</a>
                    <a href="{{ route('pending-requisitions') }}" class="dropdown-item dropdown-item-action">Pending Requisitions</a>
                    <a href="{{ route('submitted-requisitions') }}" class="dropdown-item dropdown-item-action">Submitted Requisitions</a>
                    <a href="{{ route('approved-requisitions') }}" class="dropdown-item dropdown-item-action">Approved Requisitions</a>
                    <a href="{{ route('paid-requisitions') }}" class="dropdown-item dropdown-item-action">Paid Requisitions</a>
                    {{-- <a href="{{ route('retired-requisitions') }}" class="dropdown-item dropdown-item-action">Retired Requisitions</a> --}}
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Retirement</a>
                <div class="dropdown-menu">
                    <a href="{{ route('retirements.create') }}" class="dropdown-item dropdown-item-action active">Retire Requisition</a>
                    <a href="{{ route('retirements.index') }}" class="dropdown-item dropdown-item-action">Pending Retirements</a>
                    <a href="{{ route('retirements.submitted') }}" class="dropdown-item dropdown-item-action">Submitted Retirements</a>
                    <a href="{{ route('retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Retirements</a>
                    <a href="{{route('expense-retirements.create')}}" class="dropdown-item dropdown-item-action">Create Expense Retirements</a>
                    <a href="{{ route('expense-retirements.pending') }}" class="dropdown-item dropdown-item-action">Pending Expense Retirements</a>
                    <a href="{{ route('expense-retirements.index') }}" class="dropdown-item dropdown-item-action">Submitted Expense Retirements</a>
                    <a href="{{ route('expense-retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Expense Retirements</a>
                    <a href="{{ route('expense-retirements.paid') }}" class="dropdown-item dropdown-item-action">Paid Expense Retirements</a>
                </div>
            </li>
            @elseif(Auth::user()->stafflevel_id == $normalStaff)
             <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Budget</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('budgets.create') }}" class="dropdown-item dropdown-item-action active">Create Budgets</a> -->
                    <a href="{{ route('budgets.index') }}" class="dropdown-item dropdown-item-action">View Budgets</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Requisition</a>
                <div class="dropdown-menu">
                    <!-- <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition(s)</a> -->
                    <a href="{{ route('requisitions.create') }}" class="dropdown-item dropdown-item-action active">Create Requisition</a>
                    <a href="{{ route('pending-requisitions') }}" class="dropdown-item dropdown-item-action">Pending Requisitions</a>
                    <a href="{{ route('submitted-requisitions') }}" class="dropdown-item dropdown-item-action">Submitted Requisitions</a>
                    <a href="{{ route('approved-requisitions') }}" class="dropdown-item dropdown-item-action">Approved Requisitions</a>
                    <a href="{{ route('paid-requisitions') }}" class="dropdown-item dropdown-item-action">Paid Requisitions</a>
                    {{-- <a href="{{ route('retired-requisitions') }}" class="dropdown-item dropdown-item-action">Retired Requisitions</a> --}}
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Retirement</a>
                <div class="dropdown-menu">
                    <a href="{{ route('retirements.create') }}" class="dropdown-item dropdown-item-action active">Retire Requisition</a>
                    <a href="{{ route('retirements.index') }}" class="dropdown-item dropdown-item-action">Pending Retirements</a>
                    <a href="{{ route('retirements.submitted') }}" class="dropdown-item dropdown-item-action">Submitted Retirements</a>
                    <a href="{{ route('retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Retirements</a>
                    <a href="{{route('expense-retirements.create')}}" class="dropdown-item dropdown-item-action">Create Expense Retirements</a>
                    <a href="{{ route('expense-retirements.pending') }}" class="dropdown-item dropdown-item-action">Pending Expense Retirements</a>
                    <a href="{{ route('expense-retirements.index') }}" class="dropdown-item dropdown-item-action">Submitted Expense Retirements</a>
                    <a href="{{ route('expense-retirements.confirmed') }}" class="dropdown-item dropdown-item-action">Confirmed Expense Retirements</a>
                    <a href="{{ route('expense-retirements.paid') }}" class="dropdown-item dropdown-item-action">Paid Expense Retirements</a>
                </div>
            </li>       
            @endif

        </ul>
        <ul class="navbar-nav" style="margin-right: -120px;">
            <li class="nav-item dropdown ml-2">
                <a href="#" class="nav-link dropdown-toggle dropdown-clear-caret" data-toggle="dropdown" aria-expanded="false">
                    <span class="d-none d-lg-inline-block">{{ Auth::user()->username }}</span> 
                    <img src="{{url('assets/images/avatars/andrew-robles-300868.jpg')}}" class="img-fluid rounded-circle ml-1" width="35" alt="">
                 </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="account.html" class="dropdown-item dropdown-item-action">
                        <i class="material-icons md-18 align-middle mr-1">lock</i> 
                        <span class="align-middle">Account</span>
                    </a>
                    <a href="{{route('user-profile',Auth::user()->id)}}" class="dropdown-item dropdown-item-action">
                        <i class="material-icons md-18 align-middle mr-1">account_circle</i> 
                        <span class="align-middle">Profile</span>
                    </a>
                    <a href="{{route('change-password')}}" class="dropdown-item dropdown-item-action">
                        <i class="material-icons md-18 align-middle mr-1">settings</i> 
                        <span class="align-middle">Settings</span>
                    </a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item dropdown-item-action">
                        <i class="material-icons md-18 align-middle mr-1">exit_to_app</i> 
                        <span class="align-middle">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
</nav>
