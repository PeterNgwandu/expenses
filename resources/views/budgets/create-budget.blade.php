<?php

use App\Budget\Budget;
use App\StaffLevel\StaffLevel;
use App\Http\Controllers\Budgets\BudgetsController;

$newBudgets = Budget::where('status', 'null')->orWhere('status', 'Rejected')->orWhere('status', 'Approved')->get();

$stafflevels = StaffLevel::all();

$hod = $stafflevels[0]->id;
$ceo = $stafflevels[1]->id;
$supervisor = $stafflevels[2]->id;
$normalStaff = $stafflevels[3]->id;
$financeDirector = $stafflevels[4]->id;

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
                                <div class="form-group form-row">
                                    <div class="col-lg-4">
                                        <label>Title No.</label>
                                        <div class="input-group input-group--inline">
                                            <div class="input-group-addon">
                                                <i class="material-icons">receipt</i>
                                            </div>
                                            <input disabled type="text" class="form-control" name="title_no" placeholder="Title Number" value="{{BudgetsController::generateBudgetNo()}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
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
                                    <div class="col-lg-4">
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
                        {{-- @if ($newBudgets->isEmpty())
                            <div class="alert alert-info m-2">
                                <p>Currently there are no new budgets created.</p>
                            </div>
                        @endif --}}
                        <ul class="list-group list-group-flush">
                            @foreach($newBudgets as $budget)
                                <a href="{{url('budgets/'.$budget->id)}}" style="text-decoration:none;">
                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                    <span>{{$budget->title_no}}</span>
                                    <span>{{$budget->title}} : <?php if($budget->status != 'null') {echo '&nbsp;&nbsp;&nbsp;Status =>'. ' <span class="text-instagram">'.$budget->status. '</span>';} else {
                                        echo '<span class="text-instagram">Approval Pending</span>';
                                    } ?></span>
                                    @if (Auth::user()->stafflevel_id == $financeDirector && $budget->status == 'null')    
                                        <span>
                                            
                                            <button type="button" data-id="{{$budget->id}}" style="border-radius: 0px !important" class="btn approve-budget btn-sm btn-white">Approve</button>
                                            @if ($budget->status != 'Rejected')
                                                <button type="button" data-id="{{$budget->id}}" style="background: #FCF5F4 !important; border-radius: 0px !important" class="btn reject-budget btn-sm btn-white">Reject</button>
                                            @endif
                                            <i style="cursor: pointer;" data-id="{{$budget->id}}" class="material-icons delete-budget  md-10 align-middle mb-1 mt-1 text-danger">delete_forever</i>
                                        </span>
                                    @elseif(Auth::user()->stafflevel_id == $ceo && $budget->status == 'Approved')
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
