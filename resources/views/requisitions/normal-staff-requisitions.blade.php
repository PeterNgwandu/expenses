<?php

use App\Http\Controllers\Requisitions\RequisitionsController;

?>
@extends('layout.app')

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

                            <table id="data-table" class="table table-bordered table-sm table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Requester</th>
                                            <th scope="col" class="text-center">Department</th>
                                            <th scope="col" class="text-center">Requisition No.</th>
                                            <th scope="col" class="text-center">Requisitions Gross Totals</th>
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
                                                    {{ number_format(RequisitionsController::getRequisitionTotal($requisition->req_no)) }} /=
                                                </td>
                                                <td scope="col" class="text-center">
                                                    <a href="{{url('/submitted-requisitions/'.$requisition->req_no)}}" class="btn btn-sm btn-outline-success">View All Requisitions</a>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                            </table>

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
