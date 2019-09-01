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
                                    <h4 class="card-title">Edit Department</h4>
                                    <a class="float-right" style="text-decoration: none;" href="{{url('/view-departments')}}">View Registered Departments</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-10">
                                  <form method="POST" action="{{ route('departments.update',$department->id) }}" class="form-inline float-left">
                                      @method('PUT')
                                      @csrf
                                      <div class="form-group mr-3">
                                          <label class="control-label mr-1">Department Name</label>
                                          <input type="hidden" name="company_id" value="{{ $company->id }}">
                                          <input type="text" class="form-control" name="name" placeholder="Name" value="{{$department->name}}">
                                      </div>

                                      <button type="submit" class="btn btn-primary ml-3" name="button">Update</button>
                                  </form>

                              </div>
                            </div>
                        </div>
                  </div>
                </div>
            </div>

    </div>
</div>

@endsection
