<?php


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
                                <div class="col-lg-12">
                                    <h4 class="card-title">Posted Expense Retirement Journals</h4>
                                    <p class="lead float-right" style="color: #35A45A;">
                                        
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="py-4">
                            <div class="table-responsive">

                                <table id="data-table" class="table table-bordered table-sm table-striped table-dark mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">Journal #</th>
                                                <th scope="col" class="text-center">Date Created</th>
                                                <th scope="col" class="text-center">Status</th>
                                                <th scope="col" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($journals as $journal)
                                                <tr>
                                                    <a href="#">
                                                        <td scope="col" class="text-center">{{$journal->journal_no}}</td>
                                                    </a>
                                                    <td scope="col" class="text-center">{{$journal->created_at}}</td>
                                                    <td scope="col" class="text-center">{{$journal->status}}</td>
                                                    <td scope="col" class="text-center">
                                                        <a class="btn btn-sm btn-outline-success" href="{{url('/expense-retirement-journal/'.$journal->journal_no)}}">View</a>
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
<script type="text/javascript">

</script>
