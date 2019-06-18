<?php
use App\User;

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
                                <div class="col-lg-6">
                                    <h4 class="card-title">Change Password</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-10 offset-1">
                                <form method="POST" action="{{route('post-change-password')}}">
                                    @method('PUT')
                                    @csrf

                                    <div class="row">
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="current_password" placeholder="Enter Current Password" value="">
                                        </div>
                                      </div>
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="new_password" placeholder="Enter New Password" value="">
                                        </div>
                                      </div>
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="new_password" placeholder="Confirm Password" value="">
                                        </div>
                                      </div>

                                    </div>

                                      <button style="margin-right: -10px;" type="submit" class="btn btn-primary" name="button">Update Password</button>

                                </form>
                                @if ($errors->has('current-password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                                    @endif

                                 @if ($errors->has('new-password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('new-password') }}</strong>
                                    </span>
                                    @endif
                                    
                                 @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif      
                              </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
