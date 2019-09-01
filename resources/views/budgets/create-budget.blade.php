<?php

use App\Budget\Budget;
use App\StaffLevel\StaffLevel;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Budgets\BudgetsController;

$stafflevels = StaffLevel::all();

$hod = $stafflevels[0]->id;
$ceo = $stafflevels[1]->id;
$supervisor = $stafflevels[2]->id;
$normalStaff = $stafflevels[3]->id;
$financeDirector = $stafflevels[4]->id;

if (Auth::user()->stafflevel_id == $ceo) {
    $newBudgets = Budget::where('is_active', 'Active')->where('status', 'Approved By HOD')->orWhere('status', 'Rejected')->orWhere('status', 'Approved')->orWhere('status', 'Edited, Finance')->orWhere('status', 'onprocess, ceo')->orWhere('status', 'onprocess, finance')->get();
}elseif(Auth::user()->stafflevel_id == $hod){
    $newBudgets = Budget::where('department_id', Auth::user()->department_id)->where('is_active', 'Active')->where('status', 'null')->orWhere('status', 'Rejected')->orWhere('status', 'Approved')->orWhere('status', 'Edited')->orWhere('status', 'onprocess, hod')->orWhere('status', 'onprocess')->get();
}elseif (Auth::user()->stafflevel_id == $financeDirector) {
    $newBudgets = Budget::where('is_active', 'Active')->where('status', 'Approved By HOD')->orWhere('status', 'Rejected')->orWhere('status', 'Approved')->orWhere('status', 'Edited, HOD')->orWhere('status', 'Edited, CEO')->orWhere('status', 'onprocess, finance')->orWhere('status', 'onprocess, hod')->get();
}elseif(Auth::user()->stafflevel_id == $supervisor)
{
    $newBudgets = Budget::where('is_active', 'Active')->where('status', 'onprocess')->orWhere('status', 'Rejected')->get();
}





?>
@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-account">
                        <div class="card-body">
                            <form method="POST" action="{{ route('budgets.store') }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <div class="form-group form-row">
                                    <div class="col-lg-3">
                                        <label>Title No.</label>
                                        <div class="input-group input-group--inline">
                                            <div class="input-group-addon">
                                                <i class="material-icons">receipt</i>
                                            </div>
                                            <input disabled type="text" class="form-control" name="title_no" placeholder="Title Number" value="{{BudgetsController::generateBudgetNo()}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Select Department</label>
                                        <div class="input-group input-group--inline">
                                            <div class="input-group-addon">
                                                <i class="material-icons">dashboard</i>
                                            </div>
                                            <select class="form-control" name="department_id">
                                                @foreach($departments as $dept)
                                                    <option value="{{$dept->id}}">{{$dept->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Budgets Categories</label>
                                        <div class="input-group input-group--inline">
                                            <div class="input-group-addon">
                                                <i class="material-icons">receipt</i>
                                            </div>
                                            <select class="form-control" name="budget_category_id">
                                                @foreach($budget_categories as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Budget Title</label>
                                        <div class="input-group input-group--inline">
                                            <div class="input-group-addon">
                                                <i class="material-icons">title</i>
                                            </div>
                                            <input type="text" class="form-control" name="title" placeholder="Name" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="instant-messaging">Description</label>
                                    <div class="input-group input-group--inline">
                                        <div class="input-group-addon">
                                            <i class="material-icons">description</i>
                                        </div>
                                        <textarea name="description" class="form-control" rows="8" cols="80"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Budget</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Recently Added</h4>
                            <a href="{{url('budgets')}}" data-toggle="tooltip" data-placement="bottom" title="View All Budgets" class="btn btn-sm btn-info">
                                View All
                            </a>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($budgets as $budget)
                              <a href="{{url('budgets/'.$budget->id)}}" style="text-decoration:none;">
                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                    <span>{{$budget->title_no}}</span>
                                    <span>{{$budget->title}}</span>
                                </li>
                              </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title">New Budgets</h4>
                            {{-- <small class="text-instagram">Needs Approval</small> --}}
                            <a href="{{url('budgets')}}" data-toggle="tooltip" data-placement="bottom" title="View All Budgets" class="btn btn-sm btn-info">
                                View All
                            </a>
                        </div>

                        <ul class="list-group list-group-flush">
                            @foreach($newBudgets as $budget)
                                <a href="{{url('budgets/'.$budget->id)}}" style="text-decoration:none;">
                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                    <span>{{$budget->title_no}}</span>
                                    <span>{{$budget->title}} : <?php if($budget->status != 'null') {echo '&nbsp;&nbsp;&nbsp;Status =>'. ' <span class="text-instagram">'.$budget->status. '</span>';} else {
                                        echo '<span class="text-instagram">Approval Pending</span>';
                                    } ?></span>
                                    @if($budget->user_id != Auth::user()->id)
                                    @if(Auth::user()->stafflevel_id == $hod && $budget->status == 'Edited' || $budget->status == 'onprocess')
                                        
                                        <span>
                                            
                                            <button type="button" data-id="{{$budget->id}}" style="border-radius: 0px !important" class="btn approve-budget btn-sm btn-white">Approve</button>
                                            @if ($budget->status != 'Rejected')
                                                <button type="button" data-id="{{$budget->id}}" style="background: #FCF5F4 !important; border-radius: 0px !important" class="btn reject-budget btn-sm btn-white">Reject</button>
                                            @endif
                                            <i style="cursor: pointer;" data-id="{{$budget->id}}" class="material-icons delete-budget  md-10 align-middle mb-1 mt-1 text-danger">delete_forever</i>
                                        </span>
                                        
                                    @elseif (Auth::user()->stafflevel_id == $financeDirector && $budget->status == 'Approved By HOD' || $budget->status == 'Edited, HOD' || $budget->status == 'Edited, CEO' || $budget->status == 'onprocess, hod')  
                                         
                                        <span>
                                            
                                            <button type="button" data-id="{{$budget->id}}" style="border-radius: 0px !important" class="btn approve-budget btn-sm btn-white">Approve</button>
                                            @if ($budget->status != 'Rejected')
                                                <button type="button" data-id="{{$budget->id}}" style="background: #FCF5F4 !important; border-radius: 0px !important" class="btn reject-budget btn-sm btn-white">Reject</button>
                                            @endif
                                            <i style="cursor: pointer;" data-id="{{$budget->id}}" class="material-icons delete-budget  md-10 align-middle mb-1 mt-1 text-danger">delete_forever</i>
                                        </span>
                                        
                                    @elseif(Auth::user()->stafflevel_id == $ceo && ($budget->status == 'Approved' || $budget->status == 'Edited, Finance' || $budget->status == 'onprocess, finance'))
                                        <span>
                                                
                                            <button type="button" data-id="{{$budget->id}}" style="border-radius: 0px !important" class="btn approve-budget btn-sm btn-white">Approve</button>
                                            @if ($budget->status != 'Rejected')
                                                <button type="button" data-id="{{$budget->id}}" style="background: #FCF5F4 !important; border-radius: 0px !important" class="btn reject-budget btn-sm btn-white">Reject</button>
                                            @endif
                                            <i style="cursor: pointer;" data-id="{{$budget->id}}" class="material-icons delete-budget  md-10 align-middle mb-1 mt-1 text-danger">delete_forever</i>
                                        </span>
                                    @elseif($budget->status == 'null')
                                        <span><p class="text-instagram">Needs Approval</p></span>
                                    @endif
                                    @endif
                                </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
