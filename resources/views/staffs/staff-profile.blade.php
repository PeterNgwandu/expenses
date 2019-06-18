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
                                <div class="col-lg-6">
                                    <h4 class="card-title">User Profile</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h4 class="card-title">User Details</h4>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                                    Username: {{$staff->username}}
                                                </li>
                                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                                    Email: {{$staff->email}}
                                                </li>
                                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                                    Phone: {{$staff->phone}}
                                                </li>
                                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                                    Phone 2: {{$staff->phone_alternative}}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h4 class="card-title">Staff Level &amp; Account Details</h4>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                                    Account No: {{$staff->account_no}}
                                                </li>
                                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                                    Staff Level: {{$staff_level->stafflevelname}}
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h4 class="card-title">Department Details</h4>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                                    Department: {{$staff_dept->dept_name}}
                                                </li>
                                          
                                            </ul>
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
        </div>

    </div>
</div>

@endsection
