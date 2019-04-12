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
                                    <h4 class="card-title">Add Staff</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-10 offset-1">
                                <form method="POST" action="{{ route('staffs.store') }}">
                                    @csrf
                                    <div class="row">
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="username" placeholder="Username" value="">
                                        </div>
                                      </div>
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="email" placeholder="Email" value="">
                                        </div>
                                      </div>
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="phone" placeholder="Phone" value="">
                                        </div>
                                      </div>
                                    </div>

                                    <!-- <div class="row">
                                      <div class="col-lg-6">
                                        <div class="form-group">
                                            <span> <small class="text-primary">Date of Birth</small> </span>
                                            <input type="date" class="form-control" name="dob" placeholder="Date of Birth" value="">
                                        </div><br>
                                      </div>
                                      <div class="col-lg-6">
                                        <div class="form-group">
                                            <span> <small class="text-primary">Hiring Date</small> </span>
                                            <input type="date" class="form-control" name="hiring_date" placeholder="Hiring Date" value="">
                                        </div>
                                      </div>
                                    </div> -->

                                    <div class="row">
                                      
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="phone_alternative" placeholder="Phone Alternative" value="">
                                        </div>
                                      </div>
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password" placeholder="Password" value="">
                                        </div>
                                      </div>
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" value="">
                                        </div>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-lg-6">
                                        <input type="hidden" name="company_id" value="5">
                                        <select class="form-control" name="department_id">
                                          <option value="Select Department">Select Department</option>
                                          @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="col-lg-6">
                                        <input type="hidden" name="company_id" value="1">
                                        <select class="form-control" name="stafflevel_id">
                                          <option value="Select Staff Level">Select Staff Level</option>
                                          @foreach($staff_level as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                      <div class="col-lg-6">
                                        <input class="form-control" type="text" value="{{$accounts->id}} - {{ $accounts->account_subtype_name}}" name="sub_acc_type_id" disabled="true">
                                      </div>
                                      <div class="col-lg-6">
                                        <input type="text" class="form-control" name="account_no" placeholder="Enter Account No." value="">
                                      </div>
                                    </div>
                                    <br>
                                    
                                      <button style="margin-right: -10px;" type="submit" class="btn btn-primary" name="button">Add</button>
                                   
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
