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
                                    <h4 class="card-title">Create Requisition</h4>
                                    <a href="{{route('requisitions.index')}}" class="float-right btn btn-outline-success" >View My Requisitions</a>
                                    
                                </div>
                            </div>
                        </div>

                        

                        <div id="czContainer" class="card-group">
                            <div class="card card-body bg-light ">
                              
                               
                              @if($data->isEmpty())
                              <div class="col-lg-16 render-requisition-form">
                                <!-- <form method="POST" action="{{ route('budgets.store') }}" class="form-inline float-left">
                                    @csrf
                                    <div class="form-group mr-1">
                                        <select id="budget" name="budget_id" class="form-control">
                                            <option value="Select Budget Line" selected disabled>Select Budget Line</option>
                                            @foreach($budgets as $budget)
                                                <option value="{{$budget->id}}">{{$budget->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-1">
                                        <select id="item" name="item_id" class="form-control">
                                            <option value="Select Item">Select Item</option>
                                            @foreach($items as $item)
                                                <option value="{{$item->id}}">{{$item->item_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-1">
                                        <input type="number" class="form-control" name="unit_measure" placeholder="Unit Measure" value="">
                                    </div>
                                    <div class="form-group mr-1">
                                        <input type="number" class="form-control" name="quantity" placeholder="Quantity" value="">
                                    </div>
                                    <hr>
                                    <div class="form-group mr-1">
                                        <input type="number" class="form-control" name="unit_price" placeholder="Unit Price" value="">
                                    </div>
                                    <div class="form-group mr-1">
                                        <textarea name="description" style="margin-top: 15px" cols="30" placeholder="Description" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group mr-1">
                                        <div class="input-group mb-2" style="margin-top: 10px">
                                            <div class="input-group-prepend">
                                              <div class="input-group-text" style="background: #AB2F15;color: #ffffff">Total</div>
                                            </div>
                                            <input type="text" class="form-control" name="total"  value="">
                                        </div>
                                        
                                    </div>

                                    <button type="submit" class="btn btn-primary ml-0" name="button">Create</button>

                                </form> -->
                                <form style="width: 100%;">
                                    <div class="row requisition" >

                                        <div class="mr-0">
                                            <select id="budget" name="budget_id" class="form-control">
                                                <option value="Select Budget Line" selected disabled>Select Budget</option>
                                                @foreach($budgets as $budget)
                                                    <option value="{{$budget->id}}">{{$budget->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mr-0">
                                            <select id="item" name="item_id" class="form-control requisition-item">
                                                <option value="Select Item">Select Item</option>
                                                <!-- @foreach($items as $item){{$item->id}}
                                                    <option value="{{$item->id}}">{{$item->item_name}}</option>
                                                @endforeach -->
                                            </select>
                                        </div>
                                        <div class="mr-0" style="width: 125px">
                                            <input type="text" class="form-control unit_measure" name="unit_measure" placeholder="Unit Measure" value="">
                                        </div>
                                        <div class="mr-0" style="width: 200px !important;">
                                            <input type="text" name="description"  placeholder="Description" class="form-control description">
                                        </div>
                                        <div class="" style="width: 100px">
                                            <input type="number" style="" class="form-control quantity" name="quantity" placeholder="Quantity" value="" style="">
                                        </div>
                                        <div class="mr-0">
                                            <input type="number" style="" class="form-control unit_price" name="unit_price" placeholder="Unit Price" value="">
                                        </div>


                                        <div class="mr-2">
                                            <select name="accounts" class="form-control vat">
                                                <option value="Select VAT Options" selected disabled>Select VAT Options</option>
                                                <option value="vat_inclusive">VAT Inclusive</option>
                                                <option value="vat_exclusive">VAT Exclusive</option>
                                                <option value="non_vat">Non VAT</option>
                                            </select>
                                        </div>
                                        <div class="mr-2">
                                            <select name="accounts" class="form-control">
                                                <option value="Select Account" selected disabled="">Select Account</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{$account->id}}">{{$account->account_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- <div class="mr-0">
                                            <div class="input-group mb-2" >
                                            <span class="input-group-prepend">
                                              <span class="input-group-text" style="background: #AB2F15;color: #ffffff">=</span>
                                            </span>
                                            <input type="text" class="form-control" name="total"  value="">
                                        </div>
                                        </div> -->
                                        <div style="width: 50px">
                                            <b class="new-row"><i class="material-icons md-36 align-middle mb-1" style="cursor: pointer; color: purple">add</i></b>
                                        </div>
                                            
                                    </div>

                                    <div class="row requisition render-requisition-row">
                                         
                                    </div>
                                </form>
                              </div>
                              @else
                                <div class="col-lg-16 render-requisition-form">

                                    @include('requisitions.requisition-form')

                                </div>
      
                              @endif
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
<script type="text/javascript">


    $(document).on('change', '#budget', function() {
            var budget_id = $(this).val();
            if(budget_id) {
               var url = '/get-items-list/'+budget_id;
                $.get(url, function(data) {
                    if(data){
                        $('#item').empty();
                        $('#item').focus;
                        $('#item').append('<option value="">-- Select Item --</option>'); 
                        $.each(data, function(key, value){
                        $('select[name="item_id"]').append('<option value="'+ value.id +'">' + value.item_name+ '</option>');
                    });
                  }else{
                      $('#item').empty();
                    }
                });
            }
        });

        $(document).on('click', '.new-row', function(){
            var url = '/add-new-row';
            $.get(url, function(data){
                $('.render-requisition-row').html(data.result);
            });
        });

        $(document).on('change', '.requisition-item', function(e){
            var budget = $('.budget').val();
            var item = e.target.value;
            var url = '/submit-single-row/'+budget+'/'+item;
            $.get(url, function(data){
                console.log(data.result);
                $('.render-requisition-form').html(data.result);
            });
        });

        $(document).on('change', '.vat', function(e) {
            
        });

         
        $(document).on('click', '.clear-btn', function(e) {
            if (confirm('Are you sure you want to delete this budget ?')) {
                var rowID = $(this).attr('id') // Getting the  row ID from the clear button
                var url = '/delete-row/'+rowID;
                console.log(url);
                $.get(url, function(data){
                window.location = location.href; // Refreshing the whole page after deleting the row
             });  
            }     
            
        });

        $(document).on('keyup', '.unit_measure', function(e) {
            var rowID = $(this).attr('data-id');
            var value = $('#unit_measure'+rowID).val();
            var url = '/update-item-unit-measure/'+rowID+'/'+value;
            $.get(url, function(data) {
                // window.location = location.href
                console.log(data.result);
            });
        });

        $(document).on('keyup', '.description', function(e) {
            var rowID = $(this).attr('data-id');
            var value = $('#description'+rowID).val();
            var url = '/update-item-description/'+rowID+'/'+value;
            $.get(url, function(data) {
                console.log(data.result);
            });
        });

        $(document).on('keyup', '.unit_price', function(e) {
            var rowID = $(this).attr('data-id');
            var value = $('#unit_price'+rowID).val();
            var url = '/update-item-unit-price/'+rowID+'/'+value;
            $.get(url, function(data) {
                console.log(data.result);
            });
        });

        $(document).on('keyup', '.quantity', function(e) {
            var rowID = $(this).attr('data-id');
            var value = $('#quantity'+rowID).val();
            var url = '/update-item-quantity/'+rowID+'/'+value;
            $.get(url, function(data) {
                console.log(data.result);
                window.location = location.href; // Refreshing the whole page after deleting the row
            })
        });


     
</script>