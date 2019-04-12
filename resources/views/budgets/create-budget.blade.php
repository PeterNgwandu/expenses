<?php

use App\Http\Controllers\Budgets\BudgetsController;

?>
@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">


            <!-- <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Create Budget</h4>
                                    <a href="{{url('budgets')}}" class="float-right">View All Budgets</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-12">
                                <form method="POST" action="{{ route('budgets.store') }}" class="form-inline float-left">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label">Title No.</label>
                                                <input type="text" class="form-control" name="title_no" placeholder="Title Number" value="{{BudgetsController::generateBudgetNo()}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label">Budget Title</label>
                                                <input type="text" class="form-control" name="title" placeholder="Name" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <textarea name="description" class="form-control" rows="8" cols="80"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                    <button type="submit" class="btn btn-primary ml-3" name="button">Create</button>
                                </form>

                              </div>
                            </div>
                        </div>
                </div>
            </div>
        </div> -->

        <div class="row">
            <div class="col-lg-9">
                <div class="card card-account">
                    <div class="card-body">
                        <form method="POST" action="{{ route('budgets.store') }}">
                            @csrf
                            <div class="form-group form-row">
                                <div class="col-lg-6">
                                    <label>Title No.</label>
                                    <div class="input-group input-group--inline">
                                        <div class="input-group-addon">
                                            <i class="material-icons">receipt</i>
                                        </div>
                                        <input type="text" class="form-control" name="title_no" placeholder="Title Number" value="{{BudgetsController::generateBudgetNo()}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
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
            <div class="col-lg-3">
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
        </div>

    </div>
</div>

@endsection
