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
                          <div class="col-lg-12">
                              <h4 class="card-title">Staff Level</h4>

                          </div>
                            <table class="table table-sm table-striped table-bordered mb-0">
                              <thead>
                                  <tr>
                                      <th scope="col" class="text-center">#ID</th>
                                      <th scope="col" class="text-center">Staff Level Name</th>
                                      <th scope="col" class="text-center">Status</th>
                                      @if(Auth::user()->username == 'Admin')
                                      <th scope="col" class="text-center">Action</th>
                                      @endif
                                  </tr>
                              </thead>
                              <tbody>
                                 @foreach($levels as $level)

                                  <tr>
                                      <td class="align-middle text-center">{{ $level->id }}</td>
                                      <td class="align-middle text-center">{{ $level->name }}</td>
                                      <td class="align-middle text-center">{{ $level->status }}</td>
                                      @if(Auth::user()->username == 'Admin')
                                      @if($level->status == 'Active')
                                        <td class="align-middle text-center">
                                            <a href="{{url('/disable-level/'.$level->id)}}" class="btn btn-sm btn-outline-danger">Disable</a>
                                            <a data-id="{{$level->id}}" href="{{url('/delete-level/'.$level->id)}}" class=" btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                      @endif
                                      @if($level->status == 'Disabled')
                                        <td class="align-middle text-center">
                                            <a href="{{url('/enable-level/'.$level->id)}}" class="btn btn-sm btn-outline-success">Enable</a>
                                            <a data-id="{{$level->id}}" href="{{url('/delete-level$level/'.$level->id)}}" class=" btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                      @endif
                                      @endif
                                  </tr>

                                 @endforeach
                              </tbody>
                            </table><br>
                            @if(Auth::user()->username == 'Admin')
                            <a href="{{url('/staffs')}}" class="float-right">Add Staff Level</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
          </div>



    </div>
</div>

@endsection
