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
                                    <h4 class="card-title">Register Department</h4>
                                    <a class="float-right" style="text-decoration: none;" href="{{url('/view-departments')}}">View Registered Departments</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-10">
                                  <form method="POST" action="{{ route('departments.store') }}" class="form-inline float-left">
                                      @csrf
                                      <div class="form-group mr-3">
                                          <label class="control-label mr-1">Department Name</label>
                                          <input type="hidden" name="company_id" value="{{ $company->id }}">
                                          <input type="text" class="form-control" name="name" placeholder="Name" value="">
                                      </div>

                                      <button type="submit" class="btn btn-primary ml-3" name="button">Register</button>
                                  </form>

                              </div>
                            </div>
                        </div>
                  </div>
                </div>
            </div>
            <!-- <div class="row">
              <div class="col-lg-6">
                  <div class="card-group">
                      <div class="card card-body bg-dark">
                          <div class="col-lg-12">
                              <table class="table table-sm table-striped table-dark mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">#ID</th>
                                        <th scope="col" class="text-center">Department Name</th>
                                        <th scope="col" class="text-center">Date Registered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach($departments as $department)

                                    <tr>
                                        <td class="align-middle text-center">{{ $department->id }}</td>
                                        <td class="align-middle text-center">{{ $department->name }}</td>
                                        <td class="align-middle text-center">{{ $department->created_at->toFormattedDateString() }}</td>

                                    </tr>

                                   @endforeach
                                </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
            </div> -->

    </div>
</div>

@endsection
