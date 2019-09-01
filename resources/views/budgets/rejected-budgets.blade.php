<?php
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\StaffLevel\StaffLevel;

$stafflevels = StaffLevel::all();

$hod = $stafflevels[0]->id;
$ceo = $stafflevels[1]->id;
$supervisor = $stafflevels[2]->id;
$normalStaff = $stafflevels[3]->id;
$financeDirector = $stafflevels[4]->id;
?>

@extends('layout.app')
<style media="screen">
    .mycontainer {
        max-width: 96% !important;
    }
</style>
@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container mycontainer">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded mb-3">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Budget</h4>
                                    <!-- @if(Auth::user()->username == 'Admin' || Auth::user()->stafflevel_id == $hod || Auth::user()->stafflevel_id == $ceo || Auth::user()->stafflevel_id == $financeDirector)
                                    <a href="#" class="float-right btn btn-primary" data-toggle="modal" data-target="#add_items">Add Items</a>
                                    @endif -->
                                </div>
                            </div>
                        </div>

                           <table id="data-table" class="table table-sm table-bordered table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Budget No.</th>
                                            <th scope="col" class="text-center">Title</th>
                                            <th scope="col" class="text-center">Category</th>
                                            <th scope="col" class="text-center">Description</th>
                                            <th scope="col" class="text-center">Date Created</th>
                                            <th scope="col" class="text-center">Total</th>
                                            <th scope="col" class="text-center">Is Active</th>
                                            @if(Auth::user()->username == 'Admin')
                                                <th scope="col" class="text-center">Action</th>
                                            @endif    
                                            <!-- <th scope="col" class="text-center">Commited</th>
                                            <th scope="col" class="text-center">Amount Spent</th>
                                            <th scope="col" class="text-center">Budget Balance</th> -->
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach($budgets as $budget)
                                        <tr>
                                            <td class="align-middle text-center">{{ $budget->title_no }}</td>
                                            <td class="align-middle text-left">
                                                <a class="text-info" href="{{ route('budgets.show', $budget->id) }}">{{ $budget->title }}</a>
                                            </td>
                                            <td class="align-middle text-left">{{$budget->category}}</td>
                                            <td class="align-middle text-left">{{$budget->description}}</td>
                                            <td style="width: 120px;" class="align-middle text-left">{{$budget->created_at->toFormattedDateString()}}</td>
                                            <td class="align-middle text-right">{{number_format(BudgetsController::get_sumitems_by_budgetID($budget->id))}}</td>
                                            <!-- <td class="align-middle text-right">{{number_format(BudgetsController::getCommitedAmount($budget->id))}}</td>
                                            <td class="align-middle text-right">{{number_format(BudgetsController::getSpentAmount($budget->id))}}</td>
                                            <td class="align-middle text-right">{{number_format(BudgetsController::getBudgetBalance($budget->id))}}</td> -->

                                            <td class="align-middle text-left">
                                                @if($budget->is_active == 'Active')
                                                    <span class="text-success">{{$budget->is_active}}</span>
                                                @else
                                                    <span class="text-info">{{$budget->is_active}}</span>
                                                @endif
                                            </td>

                                            @if(Auth::user()->username == 'Admin')
                                                <td class="align-middle text-center">
                                                    @if($budget->is_active == 'Active')
                                                        <a class="btn btn-sm btn-danger" href="{{route('freeze-budget',$budget->id)}}">Freeze</a>
                                                    @elseif($budget->is_active == 'Frozen')
                                                        <a class="btn btn-sm btn-twitter" href="{{route('unfreeze-budget',$budget->id)}}">Unfreeze</a>
                                                    @endif
                                                </td>
                                            @endif

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(Auth::user()->username == 'Admin' || Auth::user()->stafflevel_id == $hod || Auth::user()->stafflevel_id == $ceo || Auth::user()->stafflevel_id == $financeDirector)
                            <a href="{{url('budgets/create')}}" class="float-right">Add Another Budget</a>
                            @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

