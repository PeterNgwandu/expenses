<?php 

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Retirements\RetirementController;
use App\Http\Controllers\Requisitions\RequisitionsController;


?>

@extends('layout.app')
<style type="text/css">
    .material-icons {
        cursor: pointer;
    }
    .modal-lg {
        max-width: 95% !important;
    }
    #retirement_no {
        background: green; 
        color: #ffffff;
        margin: 2px;
        padding: 2px;
        padding-right: 16px;
        margin-right: 6px;
        border-radius: 2px;
    }
</style>
@section('content')

<div class="mdk-drawer-layout js-mdk-drawer-layout" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-header-layout__content--scrollable" style="margin-top: 30px">

        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Retire Requisition

                                        <span class="float-right">
                                            <p class="lead" style="color: #35A45A;">Paid Requisitions
                                                <span>
                                                   <i class="material-icons md-10 align-middle mb-1 text-success">done</i> 
                                                </span>
                                            </p>                                          
                                        </span>
                                    </h4>      
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                                                      
                              <table class="table table-sm table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Requester</th>
                                            <th scope="col" class="text-center">Budget Line</th>
                                            <th scope="col" class="text-center">Item Name</th>
                                            <th scope="col" class="text-center">Unit Measure</th>
                                            <th scope="col" class="text-center">Unit Price</th>
                                            <th scope="col" class="text-center">Quantity</th>
                                            <th scope="col" class="text-center">Description</th>
                                            <th scope="col" class="text-center">Requisition Date</th>
                                            <th scope="col" class="text-center">Gross Per Requisition</th>
                                            <th scope="col" class="text-center">Status</th>                                         
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paid_requisitions as $requisition)
                                            <tr>
                                                <td scope="col" class="text-center">{{$requisition->username}}</td>
                                                <td scope="col" class="text-center">{{$requisition->budget}}</td>
                                                <td scope="col" class="text-center">{{$requisition->item}}</td>
                                                <td scope="col" class="text-center">{{$requisition->unit_measure}}</td>
                                                <td scope="col" class="text-center">{{$requisition->unit_price}}</td>
                                                <td scope="col" class="text-center">{{$requisition->quantity}}</td>
                                                <td scope="col" class="text-center">{{$requisition->description}}</td>
                                                <td scope="col" class="text-center">{{$requisition->created_at->toFormattedDateString()}}
                                                </td>
                                                <td scope="col" class="text-center">
                                                    {{$requisition->unit_price * $requisition->quantity}} /=
                                                </td>
                                                <td scope="col" class="text-center">
                                                    <button class="btn btn-sm btn-outline-success"> {{$requisition->status}}
                                                    </button>
                                                </td>
                                                <td scope="col" class="text-center">
                                                    @if($requisition->user_id != Auth::user()->id)
                                                        <p>No Action</p>
                                                    @else
                                                    <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#retire{{$requisition->id}}">Retire</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    
<div class="modal fade" id="retire{{$requisition->id}}" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Retire Requisition</h5>{{$requisition->id}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span><br><br>
                        <div id="retirement_no">
                           <h5 class="float-right">{{ RetirementController::getTheLatestRetirementNumber() }}</h5>
                            &nbsp;&nbsp;&nbsp;<br> 
                        </div>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-inline">   
                <form class="form-inline data retire-form" id="data">
                    @csrf
                
                    <input type="hidden" id="req_id" name="req_id" value="<?php if(!empty($requisition->user_id)) echo($requisition->id); else echo 'Null' ?>">
                    <input class="budget_id" type="hidden" id="budget" name="budget_id" value="<?php if(!empty($requisition->user_id)) echo($requisition->budget_id); else echo 'Null' ?>">
                    <input class="item_id" type="hidden" id="item" name="item_id" value="<?php if(!empty($requisition->user_id)) echo($requisition->item_id); else echo 'Null' ?>">
                    <input type="hidden" name="ret_no" value="{{ RetirementController::getTheLatestRetirementNumber() }}">   
                    <input type="hidden" name="user_id" value="{{$requisition->user_id}}">
                    <select style="width: 140px;background: #ffffff;border: 1px solid #566573" id="supplier" name="supplier_id" class="form-control">   
                         <option value="Select Budget" selected disabled>
                             Supplier
                         </option>
                         <option value="Supplier_One">Supplier One</option>
                         <option value="Supplier_Two">Supplier Two</option>
                         <option value="Supplier_Three">Supplier Three</option>
                    </select>                     
                            
                    <input style="width: 100px;" type="text" name="ref_no" class="form-control ref_no" placeholder="Ref No.">

                    <input style="width: 100px;" type="text" placeholder="Date" name="purchase_date" class="form-control datepicker purchase_date" value="">

                    <input id="item_name" style="width: 100px;" type="text" name="item_name" class="form-control item_name" placeholder="Item" value="">
                  
                    <input style="width: 70px;" type="text" name="unit_measure" class="form-control unit_measure" placeholder="UoM" value="">
                   
                    <input style="width: 60px;" type="text" name="quantity" class="form-control quantity" placeholder="Qty" value="">
                    
                    <input style="width: 120px;" type="number" name="unit_price" class="form-control unit_price" placeholder="Price" value="">
                       
                    <select style="width: 125px;" name="vat" value="" class="form-control vat">
                        <option value="VAT_Options" selected disabled>VAT</option>
                        <option value="VAT Exclusive">VAT Exclusive</option>
                        <option value="VAT Inclusive">VAT Inclusive</option>
                        <option value="Non VAT">Non VAT</option>
                    </select>
            
                    <select id="account" style="width: 85px;" name="account_id" class="form-control accounts">
                        <option value="VAT Options" selected disabled>Account</option>
                        @foreach($accounts as $account)
                            <option value="<?php if(!empty($requisition->user_id)) echo($requisition->account_id); else echo 'Null' ?>">{{$account->account_name}}</option>
                        @endforeach
                    </select>
         
                    <input style="width: 280px;" type="text" name="description" class="form-control description" placeholder="Description">
                    
                    <span>
                       <i class="material-icons submit-retire md-10 align-middle mb-1 text-primary">add</i>
                       <i class="material-icons delete-row md-10 align-middle mb-1 text-primary">remove</i> 
                    </span>
                    <!-- <button type="submit" class="btn float-right btn-outline-primary mt-3 ml-1">Retire</button> -->
                    <br>
                    <hr><hr>
                </form>
                
                </div>
                
                    <table class="table table-sm mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-center">Select</th>
                                <th scope="col" class="text-center">Supplier</th>
                                <th scope="col" class="text-center">Reference No.</th>
                                <th scope="col" class="text-center">Purchase Date</th>
                                <th scope="col" class="text-center">Item Name</th>
                                <th scope="col" class="text-center">Unit Measure</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-center">Unit Price</th>
                                <th scope="col" class="text-center">VAT</th>
                                <th scope="col" class="text-center">Account</th>
                                <th scope="col" class="text-center">Description</th>
                            </tr>
                        </thead>
                        <tbody class="render-retired-items">
                                
                        </tbody>
                </table>

                    
                <input type="hidden" id="themax" name="themax" value="0">
                <button type="submit" id="retire" retire-no="{{RetirementController::getTheLatestRetirementNumber()}}" class="btn permanent-retire float-right btn-outline-primary mt-3 ml-1">Retire</button>
                
            </div>
        </div>
    </div>
</div>
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


@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script type="text/javascript">
    var data = [];
    var coun = 0 ;

    $(document).on('click', '.submit-retire', function(e) {
            e.preventDefault();
            var ref_no = $(this).closest('form').find('input[name=ref_no]').val();
            var purchase_date = $(this).closest('form').find('input[name=purchase_date]').val();
            var item_name2 = $(this).closest('form').find('input[name=item_name]').val();
            var unit_measure = $(this).closest('form').find('input[name=unit_measure]').val();
            var unit_price = $(this).closest('form').find('input[name=unit_price]').val();
            var quantity = $(this).closest('form').find('input[name=quantity]').val();
            var vat = $(this).closest('form').find('select[name=vat]').val();
            var description = $(this).closest('form').find('input[name=description]').val();
            alert(vat);
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $.ajax({
                type: "POST",
                url: '/submit-single-retire-row',
                data: $('.retire-form').serialize()+"&"+$.param({'ref_no':ref_no, 'item_name2':item_name2, 'purchase_date':purchase_date,'unit_measure':unit_measure,'unit_price':unit_price,'quantity':quantity,'vat':vat,'description':description}),
                dataType: "json",
                success: function(data) {
                    console.log(data.result);
                    $('.render-retired-items').html(data.result);
                    document.getElementById("data").reset();
                },
                error: function(){
                    //alert('opps error occured');
                }
            });

    });

    $(document).on('click', '.permanent-retire', function(e) {
        var retire_no = $(this).attr('retire-no');
        var url = '/permanent-retire/'+retire_no;
        $.get(url, function(data) {
            console.log(data.result);
        });
    });
        

    $(document).on('click', '.add-row', function(e) {
        // $("#data").find('select').each(function(data){


    });

    $(document).on('click', '.delete-row', function(e) {
        $("table tbody").find('input[name="record"]').each(function(){
            if($(this).is(":checked")){
                $(this).parents("tr").remove();
            }
        });
    });

</script>

<!-- data: {budget_id:budget_id,item_id:item_id,req_id:req_id,supplier_id:supplier,ref_no:ref_no,purchase_date:purchase_date,item_name:item_name,unit_measure:unit_measure,unit_price:unit_price,quantity:quantity,vat:vat,account_id:account,description:description}, -->