<?php

use App\Limits\Limit;
use Illuminate\Support\Facades\Auth;

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
                                    <h4 class="card-title">Approval Limits for Different Staff Levels</h4>

                                    @if(Auth::user()->username == 'Admin')
                                        <a href="#" class="float-right btn btn-primary" data-toggle="modal" data-target="#add_items">Set Limits</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="col-lg-12">
                                <table id="data-table" class="table table-sm table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">#ID</th>
                                            <th scope="col" class="text-center">Staff Level</th>
                                            <th scope="col" class="text-center">Maximum Amount</th>
                                            @if(Auth::user()->username == 'Admin')
                                              <th scope="col" class="text-center">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($limits as $limit)

                                        <tr>
                                            <td class="align-middle text-center">{{ $limit->id }}</td>
                                            <td class="align-middle text-center">{{ $limit->stafflevel }}</td>
                                            <td class="align-middle text-center">{{ number_format($limit->max_amount) }}</td>
                                            @if(Auth::user()->username == 'Admin')
                                              <td class="align-middle text-center">
                                                  <a href="{{route('limits.edit', $limit->id)}}" class="btn btn-sm btn-success">Adjust Limits</a>
                                              </td>
                                            @endif
                                        </tr>

                                       @endforeach
                                    </tbody>
                                </table>
                              </div>
                            </div>

                        </div>
                        @if($flash = session('message'))
                            <div class="alert alert-success">
                                <p>{{ $flash }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

    </div>
</div>

@endsection

<div class="modal fade" id="add_items" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largeModalLabel">Set Staff Approval Limits</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('limits.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group">
                                <select class="form-control" name="stafflevel_id">
                                    <option value="Select Staff Level">Select Staff Level</option>
                                    @foreach($stafflevels as $level)
                                        <option value="{{$level->id}}">{{$level->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-12">
                           <div class="form-group">
                                <input type="number" name="max_amount" class="form-control" placeholder="Set Maximum Amount">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="button">Set Limit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="adjust_limit" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largeModalLabel">Adjust Approval Limits</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group">
                                <select id="stafflevel_id" class="form-control" name="stafflevel_id">
                                    <option value="Select Staff Level">Select Staff Level</option>
                                    @foreach($stafflevels as $level)
                                        <option value="{{$level->id}}">{{$level->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-12">
                           <div class="form-group">
                                <input type="number" name="max_amount" class="form-control" value="" placeholder="Set Maximum Amount">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="button">Set Limit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

    $(document).on('change', '#stafflevel_id', function() {
        var stafflevel_id = $(this).val();
        var url = "/getMax/"+stafflevel_id;
        $.get(url, function(data){
            console.log(data.result)
            $('#max_amount').val(data.result.max_amount);
        });
    });

</script>
