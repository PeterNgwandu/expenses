<?php 

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;


// foreach ($data as $data) :

?>

@extends('layout.app')

@section('content')

<style type="text/css">
    .requisition div {
        padding: 0px; margin-left: 0px; width: 150px;
    }
    .requisition div input {
        margin: 0px; padding: 0px; width: 100%
    }
    .requisition i:hover {
        color: #fff !important; background: purple
     }
</style>
<div class="mdk-drawer-layout js-mdk-drawer-layout" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Hey {{ Auth::user()->username }}</h4>
                                    <!-- <a href="{{route('requisitions.index')}}" class="float-right btn btn-outline-success" >View My Requisitions</a> -->
                                    
                                </div>
                            </div>
                        </div>

                        

                        <div id="czContainer" class="card-group">
                            <div class="card card-body bg-light ">
                              
                               <div class="alert alert-warning">
                                   <h4>Oops!! You cannot approve your own requisition</h4>
                               </div>
                              
                              <div class="col-lg-16 ">
                                
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
<script type="text/javascript" src="{{url('js/jquery.czMore-latest.js')}}"></script>
<script type="text/javascript">
    $("#czContainer").czMore();
    $('#czContainer').czMore({styleOverride: true})
</script>
