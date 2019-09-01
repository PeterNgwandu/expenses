<?php
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\StaffLevel\StaffLevel;

?>
@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">All Budgets</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-12">
                                <table id="data-table" class="table table-sm table-bordered table-striped table-dark mb-0">
                                    <thead>
                                <tr>
                                    <th scope="col" class="text-center">Budget No.</th>
                                    <th scope="col" class="text-center">Title</th>
                                    <th scope="col" class="text-center">Description</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($budgets as $budget)
                                <tr>
                                    <td class="text-left">{{ $budget->title_no }}</td>
                                    <td class="text-left">{{ $budget->title }}</td>
                                    <td class="text-left">{{ $budget->description }}</td>
                                    <td class="text-left">
                                        <a href="{{url('budget/'.$budget->id)}}" class="text-info">Generate Report</a>
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
            </div>
        </div>

    </div>
</div>

@endsection

