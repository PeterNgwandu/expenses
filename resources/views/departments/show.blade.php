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
                                      <th scope="col" class="text-center">Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                 @foreach($departments as $department)

                                  <tr>
                                      <td class="align-middle text-center">{{ $department->id }}</td>
                                      <td class="align-middle text-center">{{ $department->name }}</td>
                                      <td class="align-middle text-center">{{ $department->created_at->toFormattedDateString() }}</td>
                                      <td class="align-middle text-center">{{ $department->status }}</td>
                                      @if($department->status == 'Active')
                                        <td class="align-middle text-center">
                                            <a href="{{url('/disable-deparment/'.$department->id)}}" class="btn btn-sm btn-outline-danger">Disable</a>
                                            <a data-id="{{$department->id}}" href="{{url('/delete-department/'.$department->id)}}" class=" btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                      @endif
                                      @if($department->status == 'Disabled')
                                        <td class="align-middle text-center">
                                            <a href="{{url('/enable-deparment/'.$department->id)}}" class="btn btn-sm btn-outline-success">Enable</a>
                                            <a data-id="{{$department->id}}" href="{{url('/delete-department/'.$department->id)}}" class=" btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                      @endif
                                  </tr>

                                 @endforeach
                              </tbody>
                            </table><br>
                            <a class="float-right" href="{{route('departments.index')}}">Add Department</a>
                        </div>
                    </div>
                </div>
            </div>
          </div>

    </div>
</div>

@endsection
