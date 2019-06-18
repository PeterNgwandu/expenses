<?php

use App\User;
use App\Department\Department;
use App\Http\Controllers\Department\DepartmentController;

$dept = Department::select('id')->get(); 
$users_with_depts = User::join('departments','users.department_id','departments.id')
                          ->select('users.department_id')->get()->pluck('department_id');

?>
@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">

          <div class="row offset-1">
            <div class="col-lg-10">
                <div class="card-group">
                    <div class="card card-body">
                        <div class="col-lg-12">
                            <table class="table table-sm table-striped table-bordered mb-0">
                              <thead>
                                  <tr>
                                      <th scope="col" class="text-center">#ID</th>
                                      <th scope="col" class="text-center">Department Name</th>
                                      <th scope="col" class="text-center">Date Registered</th>
                                      <th scope="col" class="text-center">Status</th>
                                      @if (Auth::user()->usename == 'Admin')  
                                        <th scope="col" class="text-center">Action</th>
                                      @endif
                                  </tr>
                              </thead>
                              <tbody>
                                 @foreach($departments as $department)

                                  <tr>
                                      <td class="align-middle text-center">{{ $department->id }}</td>
                                      <td class="align-middle text-center">{{ $department->name }}</td>
                                      <td class="align-middle text-center">{{ $department->created_at->toFormattedDateString() }}</td>
                                      <td class="align-middle text-center">{{ $department->status }}</td>
                                      @if (Auth::user()->username == 'Admin')
                                      @if($department->status == 'Active')
                                        <td class="align-middle text-center">
                                          <a href="{{url('/disable-deparment/'.$department->id)}}" class="btn btn-sm btn-outline-danger">Disable</a>
                                          @if(!$users_with_depts->contains($department->id))
                                              <a data-id="{{$department->id}}" href="{{url('/delete-department/'.$department->id)}}" class=" btn btn-sm btn-danger">Delete</a> 
                                            @else  

                                            @endif
                                        </td>
                                        @endif
                                        @if($department->status == 'Disabled')
                                        <td class="align-middle text-center">
                                            <a href="{{url('/enable-deparment/'.$department->id)}}" class="btn btn-sm btn-outline-success">Enable</a>

                                            <a data-id="{{$department->id}}" href="{{url('/delete-department/'.$department->id)}}" class=" btn btn-sm btn-danger">Delete</a> 

                                        </td>
                                        @endif   
                                      @endif
                                      
                                  </tr>

                                 @endforeach
                              </tbody>
                            </table><br>
                            @if(Auth::user()->username == 'Admin')
                            <a class="float-right" href="{{route('departments.index')}}">Add Department</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
          </div>

    </div>
</div>

@endsection
