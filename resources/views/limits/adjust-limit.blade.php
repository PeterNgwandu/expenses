<?php
use App\Limits\Limit;


?>

@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">

          <div class="row">
              <div class="col-lg-12">
                  <div class="card card-account">
                      <div class="card-body">
                          <form method="POST" action="{{ route('limits.update', $limit->id) }}">
                              @method('PUT')
                              @csrf
                              <div class="form-group form-row">
                                  <div class="col-lg-6">
                                      <label>Staff Level</label>
                                      <div class="input-group input-group--inline">
                                          <div class="input-group-addon">
                                              <i class="material-icons">person</i>
                                          </div>
                                          <select class="form-control" name="stafflevel_id">
                                              <option value="Select Staff Level">Select Staff Level</option>
                                              @foreach($stafflevels as $level)
                                                  <option value="{{$level->id}}">{{$level->name}}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-lg-6">
                                      <label>Budget Title</label>
                                      <div class="input-group input-group--inline">
                                          <div class="input-group-addon">
                                              <i class="material-icons">receipt</i>
                                          </div>
                                          <input type="number" name="max_amount" value="{{$limit->max_amount}}" class="form-control" placeholder="Set Maximum Amount">
                                      </div>
                                  </div>
                              </div>
                              <button type="submit" class="btn btn-primary">Adjust Limit</button>
                          </form>
                      </div>
                  </div>
              </div>

    </div>
  </div>
</div>

@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script src="{{url('assets/vendor/jquery.dataTables.js')}}"></script>
<script src="{{url('assets/vendor/dataTables.bootstrap4.js')}}"></script>

<script type="text/javascript">
    $("#data-table").DataTable();

    $(function() {
       $('#flash').delay(500).fadeIn('normal', function() {
          $(this).delay(2500).fadeOut();
       });
    });

</script>
