<?php 

use App\Http\Controllers\Requisitions\RequisitionsController;

 ?>
@extends('layout.app')
<style type="text/css">
    .mydata {
        display: none;
    }
    .preload {
        margin: 0px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        margin-top: 10px;
        background: #ffffff;
    }
    .img {
        background: #ffffff;
    }
</style>
@section('content')
<div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <h4 class="card-title">Submitted Requisitions</h4>
                                </div>
                            </div>
                        </div>
                            <div class="py-4">
                                <div class="table-responsive">
                                        <p class="text-twitter ml-4">Filter by Date</p>
                                        <form action="{{ route('submitted_filter_by_date') }}" method="POST" class="form-inline ml-4">
                                            @csrf
                                            <div class="input-group input-group--inline">
                                                <div class="input-group-addon">
                                                    <i class="material-icons">date_range</i>
                                                </div>
                                                <input type="text" class="form-control datepicker" name="from" placeholder="From" value="">&nbsp;
                                            </div>
                                            <div class="input-group input-group--inline">
                                                <div class="input-group-addon">
                                                    <i class="material-icons">date_range</i>
                                                </div>
                                                <input type="text" class="form-control datepicker" name="to" placeholder="To" value="">&nbsp;
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-twitter">Filter</button>
                                        </form>
                                    @if(Auth::user()->stafflevel_id == 5)
                            <table id="data-table" class="table table-sm table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Requester</th>
                                            <th scope="col" class="text-center">Department</th>
                                            <th scope="col" class="text-center">Requisition No</th>
                                            <th scope="col" class="text-center">Totals</th>
                                            <th scope="col" class="text-center">Action</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submitted_requisitions as $requisition)
                                            <tr>
                                                <td scope="col" class="text-center">{{$requisition->username}}</td>
                                                <td scope="col" class="text-center">{{$requisition->department}}</td>
                                                <td scope="col" class="text-center">{{$requisition->req_no}}</td>
                                                <td scope="col" class="text-success text-center font-weight-bold">
                                                    {{ number_format(RequisitionsController::getRequisitionTotal($requisition->req_no),2) }} /=
                                                </td>
                                                <td scope="col" class="text-center">
                                                    <a href="{{url('/submitted-requisitions/'.$requisition->req_no)}}" class="btn btn-sm btn-outline-success">View All Requisitions</a>
                                                </td>
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                            </table>
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

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script src="{{url('assets/vendor/jquery.dataTables.js')}}"></script>
<script src="{{url('assets/vendor/dataTables.bootstrap4.js')}}"></script>

<script type="text/javascript">
    $("#data-table").DataTable();
</script>
<script type="text/javascript">

    
    $(document).ready(function() {
        $('.preload').fadeOut('3000', function() {
            $('.mydata').fadeIn('2000');
        });
    });

</script>