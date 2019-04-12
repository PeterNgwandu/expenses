<?php

use App\Requisition\Requisition;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Requisitions\RequisitionsController;

// foreach ($data as $data) :

$requisitions = Requisition::where('requisitions.id', $id)
                          ->join('budgets','requisitions.budget_id','budgets.id')
                          ->join('items','requisitions.item_id','items.id')
                          ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                          ->get();

?>

@extends('layout.app')

@section('content')

<style type="text/css">
    .reqiuisition-container {
        max-width: 120% !important;
     }
    #budget {
        margin-top: -150px;
        margin-bottom: 10px;
        background: #E5E8E8;
    }
    #item_name {
        margin-left: -140px;
    }
</style>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container reqiuisition-container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Edit Requisition</h4>
                                    <span class="float-right">
                                        <p class="lead" style="color: #35A45A;">
                                            {{ $requisition->req_no }}
                                        </p>
                                        <p>Requisition Line No. {{$requisition->serial_no}}</p>
                                    </span>

                                </div>
                            </div>
                        </div>



                        <div id="czContainer" class="card-group">
                            <div class="card card-body bg-light ">

                              <!-- <div class="col-lg-16 render-requisition-form">
                                <form method="POST" action="{{route('requisition.create')}}">


                                    @csrf
                                    <input type="hidden" name="req_no">
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn  float-right btn-outline-primary mt-3">Submit Requisition</button>
                                             <select style="width: 154px;background: #ffffff;border: 1px solid #566573" id="budget" name="budget_id" class="form-control budget">
                                                 <option value="Select Budget" selected disabled>
                                                     Select Budget
                                                 </option>
                                                 @foreach($budgets as $budget)
                                                    <option value="{{$budget->id}}">
                                                        {{$budget->title}}
                                                    </option>
                                                 @endforeach
                                             </select>
                                        </div>
                                    </div>
                                    <div class="row requisition mt-3">
                                        <div class="col-2">
                                             <select id="item" name="item_id" class="form-control item requisition-item">
                                                 <option value="Select Budget Line" selected disabled>
                                                    Budget Line
                                                 </option>
                                                 @foreach($items as $item)
                                                    <option value="{{$item->id}}">
                                                        {{$item->item_name}}
                                                    </option>
                                                 @endforeach
                                             </select>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" value="" class="form-control" placeholder="Budget Line Description">
                                        </div>
                                        <div class="col">
                                            <input type="text" name="item_name" class="form-control item_name" placeholder="Item Name">
                                        </div>
                                        <div class="col-4">
                                            <input type="text" value="" name="description" class="form-control" placeholder="Item Description">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col">
                                            <input type="text" name="unit_measure" class="form-control" placeholder="Unit of Measure">
                                        </div>
                                        <div class="col">
                                            <input type="number" name="quantity" class="form-control" placeholder="Quantity">
                                        </div>
                                        <div class="col">
                                            <input type="number" name="unit_price" class="form-control" placeholder="Unit Price">
                                        </div>
                                        <div class="col">
                                            <select name="vat" class="form-control">
                                                <option value="VAT Options">VAT Options</option>
                                                <option value="VAT Exclusive">VAT Exclusive</option>
                                                <option value="VAT Inclusive">VAT Inclusive</option>
                                                <option value="Non VAT">Non VAT</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select name="account_id" class="form-control accounts">
                                                <option value="VAT Options">Select Account</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{$account->id}}">
                                                        {{$account->account_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>   -->

                                    <!-- <button type="submit" class="btn  btn-outline-primary mt-3">Create Requisition</button> -->
                                    <!-- <button type="button" class="btn btn-sm btn-outline-info mt-3 new-row">
                                        <span><i class="material-icons md-30 align-middle  text-primary">add</i>Add Budget Line </span>
                                    </button> -->

                                    <!-- <div class="render-requisition-row">

                                    </div> -->

                               <!--  </form>
                              </div> -->
                                <!-- <p>
                                <span>
                                   <i class="material-icons md-10 align-middle mb-1 text-info">info</i>
                                </span>
                                Budget Is Optional
                                </p>  -->


                              <div class="form-inline">

                                    <form class="form-inline data edit-form" id="data">

                                        @csrf
                                        <input type="hidden" name="req_no" value="{{$requisition->req_no}}">
                                        <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                                        <input type="hidden" name="serial_no" value="{{$requisition->serial_no}}">
                                        <select style="width: 140px;background: #ffffff;border: 1px solid #566573" id="budget" name="budget_id" class="form-control" data-toogle="tooltip" data-placement="top" title="Select Budget">
                                             <option value="Select Budget" selected disabled>
                                                 Budget
                                             </option>
                                             <option value="0">No Budget</option>
                                             @foreach($budgets as $budget)
                                                <option value="{{$budget->id}}">{{$budget->title}}</option>
                                             @endforeach
                                        </select>
                                        <select id="item_name" style="width: 140px;background: #ffffff;border: 1px solid #566573" name="item_id" class="form-control item" data-toogle="tooltip" data-placement="top" title="Select Budget Line">
                                             <option value="Select Budget" selected disabled>
                                                 Budget Line
                                             </option>
                                             @foreach($items as $item)
                                                <option value="{{$item->id}}">{{$item->item_name}}</option>
                                             @endforeach
                                        </select>

                                        <input id="line_description" type="text" style="width: 370px;" value="" class="form-control" placeholder="Budget Line Description" data-toogle="tooltip" data-placement="top" title="Budget Line Description">
                                        <input id="item_name" style="width: 160px;" type="text" name="item_name" class="form-control item_name" value="{{$requisition->item_name}}" placeholder="Item" data-toogle="tooltip" data-placement="top" title="Item To Purchase">

                                        <input style="width: 70px;" type="text" name="unit_measure" class="form-control unit_measure" placeholder="UoM" value="{{$requisition->unit_measure}}" data-toogle="tooltip" data-placement="top" title="Unit of Measure">

                                        <input style="width: 60px;" type="text" name="quantity" class="form-control quantity" placeholder="Qty" value="{{$requisition->quantity}}" data-toogle="tooltip" data-placement="top" title="Quantity">

                                        <input style="width: 120px;" type="number" name="unit_price" class="form-control unit_price" placeholder="Price" value="{{$requisition->unit_price}}" data-toogle="tooltip" data-placement="top" title="Unit Price">

                                        <select style="width: 125px;" name="vat" value="" class="form-control vat" data-toogle="tooltip" data-placement="top" title="Select VAT Options">
                                            <option value="VAT_Options" selected disabled>VAT</option>
                                            <option value="VAT Exclusive">VAT Exclusive</option>
                                            <option value="VAT Inclusive">VAT Inclusive</option>
                                            <option value="Non VAT">Non VAT</option>
                                        </select>

                                        <select id="account" style="width: 105px;" name="account_id" class="form-control accounts" data-toogle="tooltip" data-placement="top" title="Select Account">
                                            <option value="VAT Options" selected disabled>Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}">{{$account->account_name}}</option>
                                            @endforeach
                                        </select>

                                        <input style="width: 280px;" type="text" name="description" class="form-control description" placeholder="Description" value="{{$requisition->description}}" data-toogle="tooltip" data-placement="top" title="Description of Item to Purchase">

                                        <span>
                                            <button  type="submit" req-id="{{$requisition->id}}" class="btn btn-sm edit-requisition float-right btn-primary mt-1 ">Edit
                                            </button>
                                        </span>
                                        <!-- <button type="submit" class="btn float-right btn-outline-primary mt-3 ml-1">Retire</button> -->
                                        <br>
                                        <hr><hr>
                                    </form>

                                    <h5 style="margin-top:20px;" class="lead text-primary">
                                      <span>
                                         <i style="cursor: pointer;" class="material-icons submit-requisition md-10 align-middle mb-1 text-primary">receipt</i>
                                      </span>
                                      Summary</h5>
                                    <div class="col-lg-12" style="margin-left:-30px;">
                                        <div class="col-lg-6 mt-2">
                                                <table class="table table-sm table-striped table-bordered">
                                                    @if(!$requisitions->isEmpty())
                                                    <thead>
                                                        <tr>
                                                            <th>Requisition Details</th>
                                                        </tr>
                                                        <tr>
                                                            <th  scope="col" class="text-center">Requisition No.</th>
                                                            <th scope="col" class="text-center">Budget</th>
                                                            <th scope="col" class="text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($requisition->budget_id != 0)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$requisitions[0]->req_no}}</td>
                                                           <td scope="col" class="text-center">{{$requisitions[0]->budget}}</td>
                                                           @if($requisitions[0]->status == 'onprocess')
                                                            <td scope="col" class="text-center text-danger">{{$requisitions[0]->status}}</td>
                                                           @else
                                                           <td scope="col" style="color:#088958" class="text-center">{{$requisitions[0]->status}}</td>
                                                           @endif
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                    @endif
                                                    @if($requisitions->isEmpty())
                                                    <thead>
                                                        <tr>
                                                            <th>Requisition Details</th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="col" class="text-center">Requisition No.</th>
                                                            <th scope="col" class="text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                            @if($req[0]->budget_id == 0)
                                                                <tr>
                                                               <td scope="col" class="text-center">{{$req[0]->req_no}}</td>
                                                               @if($req[0]->status == 'onprocess')
                                                                <td scope="col" class="text-center text-danger">{{$req[0]->status}}</td>
                                                               @endif
                                                               <td scope="col" style="color:#088958" class="text-center">{{$req[0]->status}}</td>

                                                            </tr>
                                                            @endif
                                                    </tbody>
                                                    @endif
                                                </table>
                                            </div>
                                    </div>
                                    <div style="margin-left:-30px;" class="col-lg-12">
                                        <div class="col-lg-12 mt-2">
                                                <table class="table table-sm table-striped table-bordered">
                                                    @if(!$requisitions->isEmpty())
                                                    <thead>
                                                        <tr>
                                                            <th>Totals Summary</th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="col" class="text-center">Serial No.</th>
                                                            <th scope="col" class="text-center">Budget Line</th>
                                                            <th scope="col" class="text-center">Item Name</th>
                                                            <th scope="col" class="text-center">Desciption</th>
                                                            <th scope="col" class="text-center">Requisition Date</th>
                                                            <th scope="col" class="text-center">Unit of Measure</th>
                                                            <th scope="col" class="text-center">Quantity</th>
                                                            <th scope="col" class="text-center">Unit Price</th>
                                                            <th scope="col" class="text-center">VAT Amount</th>
                                                            <th scope="col" class="text-center">Gross Amount</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($requisitions as $requisition)
                                                            <tr>
                                                               <td scope="col" class="text-center">{{$requisition->serial_no}}</td>
                                                               <td scope="col" class="text-center">{{$requisition->item}}</td>
                                                               <td scope="col" class="text-center">{{$requisition->item_name}}</td>
                                                               <td scope="col" class="text-center">{{$requisition->description}}</td>
                                                               <td scope="col" class="text-center">{{$requisition->created_at->toFormattedDateString()}}</td>
                                                               <td scope="col" class="text-center">{{$requisition->unit_measure}}</td>
                                                               <td scope="col" class="text-center">{{$requisition->quantity}}</td>
                                                               <td scope="col" class="text-center">{{number_format($requisition->unit_price,2)}}</td>
                                                               <td scope="col" class="text-center">{{number_format($requisition->vat_amount,2)}}</td>
                                                               <td scope="col" class="text-center">{{number_format($requisition->gross_amount,2)}}</td>

                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td scope="col" class="text-center">Total</td>
                                                            <td scope="col" class="text-center">{{number_format(RequisitionsController::getRequisitionTotal($requisition->req_no),2)}}</td>


                                                        </tr>
                                                    </tbody>
                                                    @endif
                                                    @if($requisitions->isEmpty())
                                                    <thead>
                                                        <tr>
                                                            <th>Totals Summary</th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="col" class="text-center">Serial No.</th>
                                                            <th scope="col" class="text-center">Item Name</th>
                                                            <th scope="col" class="text-center">Desciption</th>
                                                            <th scope="col" class="text-center">Unit of Measure</th>
                                                            <th scope="col" class="text-center">Quantity</th>
                                                            <th scope="col" class="text-center">Unit Price</th>
                                                            <th scope="col" class="text-center">VAT Amount</th>
                                                            <th scope="col" class="text-center">Gross Amount</th>
                                                            <th scope="col" class="text-center">Amount Paid</th>
                                                            <th scope="col" class="text-center">Amount Remained</th>
                                                            <th scope="col" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $req = Requisition::where('req_no', $req_no)->where('budget_id',0)->get(); ?>
                                                        @foreach($req as $req)
                                                            <tr>
                                                               <td scope="col" class="text-center">{{$req->serial_no}}</td>
                                                               <td scope="col" class="text-center">{{$req->item_name}}</td>
                                                               <td scope="col" class="text-center">{{$req->description}}</td>
                                                               <td scope="col" class="text-center">{{$req->unit_measure}}</td>
                                                               <td scope="col" class="text-center">{{$req->quantity}}</td>
                                                               <td scope="col" class="text-center">{{number_format($req->unit_price,2)}}</td>
                                                               <td scope="col" class="text-center">{{number_format($req->vat_amount,2)}}</td>
                                                               <td scope="col" class="text-center">{{number_format($req->gross_amount,2)}}</td>
                                                               <td scope="col" class="text-center">{{number_format(RequisitionsController::getAmountPaid($req->req_no,$req->serial_no),2)}}</td>
                                                               <td scope="col" class="text-center">{{number_format(RequisitionsController::getTotalPerSerialNo($req->req_no,$req->serial_no) - RequisitionsController::getAmountPaid($req->req_no,$req->serial_no),2)}}</td>
                                                               <td scope="col" class="text-center"><a href="{{url('edit-requisition/'.$requisition->id)}}" class="btn btn-sm btn-outline-info">Edit</a></td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td scope="col" class="text-center">Total</td>
                                                            <td scope="col" class="text-center">{{number_format(RequisitionsController::getRequisitionTotal($requisition->req_no),2)}}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="width: 150px;" scope="col" class="text-center">

                                                                @if($requisition->status == 'Paid')

                                                                        <a id="approveBtn" href="{{url('approve-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-info">
                                                                         Process Payment
                                                                        </a>


                                                                @elseif($requisition->user_id != Auth::user()->id)

                                                                    <a id="approveBtn" href="{{url('approve-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-info">Approve</a>
                                                                    <a href="{{url('reject-requisition/'.$requisition->req_no)}}" class="btn btn-sm btn-warning">Reject</a>
                                                                <!-- <p>No Action</p> -->
                                                                @endif


                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    @endif
                                                </table>

                                            </div>

                                    </div>

                                </div>

                                <!-- <table class="table table-sm mb-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" class="text-center">Select</th>
                                                <th scope="col" class="text-center">Budget</th>
                                                <th scope="col" class="text-center">Budget Line</th>
                                                <th scope="col" class="text-center">Requisition No.</th>
                                                <th scope="col" class="text-center">Item Name</th>
                                                <th scope="col" class="text-center">Unit Measure</th>
                                                <th scope="col" class="text-center">Qty</th>
                                                <th scope="col" class="text-center">Unit Price</th>
                                                <th scope="col" class="text-center">VAT</th>
                                                <th scope="col" class="text-center">Account</th>
                                                <th scope="col" class="text-center">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="render-requisition">

                                        </tbody>
                                    </table>
                                    <div class="">
                                        <div class="col-lg-2 float-right" style="margin-right: -15px">
                                           <a href="{{url('submitted-requisitions',$requisition->req_no)}}" class="btn float-right btn-outline-primary mt-3 ml-1">Ok</a>
                                        </div>
                                    </div>

                            </div> -->
                        </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection
<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<!-- <script type="text/javascript" src="{{url('js/jquery.czMore-latest.js')}}"></script>
<script type="text/javascript">
    $("#czContainer").czMore();
    $('#czContainer').czMore({styleOverride: true})
</script> -->
<script type="text/javascript">


        $(document).on('change', '#budget', function() {
            var budget_id = $(this).val();
            if(budget_id) {
               $(".item").show();
               $("#line_description").show();
               $( ".item" ).prop( "disabled", false );
               $( "#line_description" ).prop( "disabled", false );

               var url = '/get-items-list/'+budget_id;
                $.get(url, function(data) {
                    if(data){
                        $('.item').empty();
                        $('.item').focus;
                        $('.item').append('<option value="">-- Select Item --</option>');
                        $.each(data, function(key, value){
                        $('select[name="item_id"]').append('<option value="'+ value.id +'">' + value.item_name+ '</option>');
                    });
                  }else{
                      $('.item').empty();
                    }
                });
            }
            if(budget_id == 0){
                $(".item").hide();
                $("#line_description").hide();
                $( ".item" ).prop( "disabled", true );
                $( "#line_description" ).prop( "disabled", true );
            }
        });

        // $(document).on('click', '.new-row', function(){
        //     var url = '/add-new-form';
        //     $.get(url, function(data){
        //         $('.render-requisition-row').html(data.result);
        //     });
        // });

        // $(document).on('change', '.accounts', function(e){
        //     var budget = $('.budget').val();
        //     var item = e.target.value;
        //     var accounts = e.target.value;
        //     var url = '/submit-single-row/'+budget+'/'+item+'/'+accounts;
        //     $.get(url, function(data){
        //         console.log(data.result);
        //         $('.render-requisition-row').html(data.result);
        //     });
        // });

        $(document).on('change', '.item', function(e) {
            var itemId = $(this).val();
            var url = '/get-item-description/'+itemId;
            $.get(url, function(data) {
                console.log(data.result.description);
                $('#line_description').val(data.result.description);

            })
        });

        // function updateValue()
        // {
        //     $('#line_description').val($('.item').val(data));
        // }

        $(document).on('change', '#item', function(e) {
            var item_id = $(this).val();
            var url = 'create-requisition/'+item_id;
            $.get(url, function(data) {
                console.log(data.result);
            });
        });

        $(document).on('click', '.edit-requisition', function(e) {
            e.preventDefault();
            var req_id = $(this).attr('req-id');
            var serial_no = $(this).closest('form').find('input[name=serial_no]').val();
            var budget_id = $(this).closest('form').find('select[name=budget_id]').val();
            var item_id = $(this).closest('form').find('select[name=item_id]').val();
            var req_no = $(this).closest('form').find('input[name=req_no]').val();
            var item_name2 = $(this).closest('form').find('input[name=item_name]').val();
            var unit_measure = $(this).closest('form').find('input[name=unit_measure]').val();
            var unit_price = $(this).closest('form').find('input[name=unit_price]').val();
            var quantity = $(this).closest('form').find('input[name=quantity]').val();
            var vat = $(this).closest('form').find('select[name=vat]').val();
            var account_id = $(this).closest('form').find('select[name=account_id]').val();
            var description = $(this).closest('form').find('input[name=description]').val();
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $.ajax({
                type: "POST",
                url: '/update-requisition/'+req_id,
                data: $('.edit-form').serialize()+"&"+$.param({'serial_no':serial_no,'req_no':req_no,'budget_id':budget_id,'item_id':item_id,'item_name2':item_name2, 'unit_measure':unit_measure,'unit_price':unit_price,'quantity':quantity,'vat':vat,'description':description,'account_id':account_id}),
                dataType: "json",
                success: function(data) {
                    console.log(data.result);
                    // $('.render-requisition').html(data.result);
                    console.log(data.result);
                    window.location = "/submitted-requisitions/"+req_no;
                },
                error: function(){
                    //alert('opps error occured');
                }
            });

        });

        // $(document).on('click', '.edit-requisition', function(e) {
        //     var req_id = $(this).attr('req-id');
        //     var url = '/update-requisition/'+req_id;
        //     $.get(url, function(data) {
        //         console.log(data.result);
        //     });
        // });


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
