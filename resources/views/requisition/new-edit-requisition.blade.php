<?php
use App\Requisition\Requisition;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Requisitions\RequisitionsController;

// foreach ($data as $data) :

?>

@extends('layout.app')

@section('content')

<style type="text/css">
     .reqiuisition-container {
        max-width: 100% !important;
     }
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
    #myProgress {
  width: 100%;
  background-color: #ddd;
}

#myBar {
  width: 1%;
  height: 30px;
  background-color: #03AA6B;
}
/* #budget {
  margin-top: -120px;
  margin-bottom: 10px;
  background: #E5E8E8;
} */
/* #item_name {
  margin-left: -140px;
} */
input,
input::-webkit-input-placeholder {
    font-size: 12px;
    line-height: 3;
}
select,option {
   font-size: 13px;
}
</style>
<div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
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
                                    <a href="{{url('submitted-requisitions/'.$requisition->req_no)}}" req-no="{{$requisition->req_no}}" data-value="{{Auth::user()->id}}" style="border-radius: 0px !important;" class="btn btn-sm reset-back btn-twitter mt-2">
                                        <span>
                                            <i style="cursor: pointer;" class="material-icons  md-2 align-middle">keyboard_arrow_left</i>
                                        </span>
                                        Go Back
                                    </a>
                                    <span class="float-right">
                                        <p class="lead" id="requisition_number" data-value="{{$requisition->req_no}}" style="color: #35A45A;">
                                            {{$requisition->req_no}}
                                        </p>
                                    </span>
                                    <a href="{{url('/reset')}}" req-no="{{$requisition->req_no}}" data-value="{{Auth::user()->id}}" class="btn btn-sm btn-twitter reset float-right" style="margin-top: -2px; margin-right:25px; border-radius:0px !important">Reset</a>

                                </div>
                            </div>
                        </div>

                        <div id="czContainer" class="card-group">
                            <div class="card card-body bg-light ">
                              <div class="form-inline">

                                    <form class="form-inline data requisition-form" id="data">
                                        @csrf
                                        <input type="hidden" id="requisition_number" name="req_no" value="{{$req_no}}">
                                        <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                                        <input type="hidden" name="budget_id" value="{{$budget_id}}">


                                        <select id="item_name" style="width: 130px;background: #ffffff;border: 1px solid #566573" name="item_id" class="form-control item" data-toogle="tooltip" data-placement="top" title="Select Budget Line">
                                             <option value="Select Budget" selected disabled>
                                                 Budget Line
                                             </option>
                                             @foreach($items as $item)
                                                <option value="{{$item->id}}">{{$item->item_name}}</option>
                                             @endforeach
                                        </select>
                                        <input id="line_description" type="text" style="width: 360px;margin-right: 50px;" value="" disabled class="form-control" placeholder="Budget Line Description" data-toogle="tooltip" data-placement="top" title="Budget Line Description">

                                        <input disabled id="activity_name" style="width: 185px; margin-right: 10px; margin-left: -55px;" type="text" name="activity_name" class="form-control activity_name" placeholder="Activity Name" data-toogle="tooltip" data-placement="top" title="Activity Name" value="{{$requisition->activity_name}}">

                                        <input  id="item_name2" style="width: 150px;" type="text" name="item_name" class="form-control item_name" placeholder="Item" data-toogle="tooltip" data-placement="top" title="Item To Purchase" value="">
                                        <input  id="unit_measure" style="width: 70px;" type="text" name="unit_measure" class="form-control unit_measure" placeholder="UoM" data-toogle="tooltip" data-placement="top" title="Unit of Measure" value="">
                                        <input  id="quantity" style="width: 60px;" type="text" name="quantity" class="form-control quantity" placeholder="Qty" data-toogle="tooltip" data-placement="top" title="Quantity" value="">
                                        <input  id="unit_price" style="width: 120px;" type="number" name="unit_price" class="form-control unit_price" placeholder="Price" data-toogle="tooltip" data-placement="top" title="Unit Price" value="">
                                        <select  style="width: 170px;" name="vat" value="" class="form-control vat" data-toogle="tooltip" data-placement="top" title="Select VAT Options">
                                            <option value="VAT_Options" selected disabled>VAT Options</option>
                                            <option value="VAT Exclusive">Exclusive</option>
                                            <option value="VAT Inclusive">Inclusive</option>
                                            <option value="Non VAT">Non VAT</option>
                                        </select>
                                        <select id="account" style="width: 190px;" name="account_id" class="form-control accounts" data-toogle="tooltip" data-placement="top" title="Select Account">
                                            <option value="VAT Options" selected disabled>Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}">{{$account->account_name}}</option>
                                            @endforeach
                                        </select>
                                        <input  id="description" style="width: 280px;" type="text" name="description" class="form-control description" data-toogle="tooltip" data-placement="top" title="Description of Item to Purchase" placeholder="Description">
                                        &nbsp;
                                        <button style="height:35px;" class="btn  btn-sm btn-twitter submit-new-requisition">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons submit-new-requisition md-10 align-middle mb-1 text-white">add_circle</i>
                                                Add Line
                                             </span>
                                        </button>
                                        <br>
                                        <hr><hr>
                                    </form>


                                    </div>


                                <table class="table table-sm mb-0 mt-3">
                                      <thead class="thead-dark">
                                          <tr>
                                              <th scope="col" class="text-center">Budget</th>
                                              <th scope="col" class="text-center">Budget Line</th>
                                              <th scope="col" class="text-center">Requisition No.</th>
                                              <th scope="col" class="text-center">Activity Name</th>
                                              <th scope="col" class="text-center">Item Name</th>
                                              <th scope="col" class="text-center">Unit Measure</th>
                                              <th scope="col" class="text-center">Qty</th>
                                              <th scope="col" class="text-center">Unit Price</th>
                                              <th scope="col" class="text-center">VAT</th>
                                              <th scope="col" class="text-center">Account</th>
                                              <th scope="col" class="text-center">Description</th>
                                              <th scope="col" class="text-center">Action</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                            <?php

                                                $requisition = Requisition::join('items','requisitions.item_id','items.id')->join('accounts','requisitions.account_id','accounts.id')->join('budgets','requisitions.budget_id','budgets.id')->select('requisitions.*','budgets.title as budget','items.item_name as item','accounts.account_name as account')->where('req_no', $req_no)->where('requisitions.status', '!=', 'Deleted')->where('requisitions.status', '!=', 'Edited')->get();
                                                $requisition_no_budget = Requisition::join('accounts','requisitions.account_id','accounts.id')->select('requisitions.*','accounts.account_name as account')->where('req_no', $req_no)->where('requisitions.status', '!=', 'Deleted')->where('requisitions.status', '!=', 'Edited')->get();
                                            ?>
                                            @if (!$requisition->isEmpty())
                                            @foreach ($requisition as $requisition)


                                                <tr>
                                                        <td scope="col" class="text-center">

                                                            <input disabled data-id="{{$requisition->id}}" id="perm_budget_id" class="form-control" type="text" name="budget_id" value="<?php if($requisition->budget_id != 0) echo $requisition->budget; else echo 'No Budget'; ?>">
                                                        </td>
                                                        <td scope="col" class="text-center"><select  data-id="{{$requisition->id}}" style="width:110px;" id="perm_item_id" class="form-control budget_line" name="item_id">
                                                                <option value="<?php if($requisition->item_id != null) echo $requisition->item; else echo 'No Budget Line'; ?>"><?php if($requisition->item_id != null) echo $requisition->item; else echo 'No Budget Line'; ?></option>
                                                                @foreach ($items as $item)
                                                                    <option value="{{$item->id}}">{{$item->item_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td scope="col" class="text-center"><input disabled data-id="{{$requisition->id}}" style="width:90px;" id="perm_req_no" class="form-control" type="text" name="req_no" disabled value="{{$requisition->req_no}}"></td>
                                                        <td scope="col" class="text-center"><input disabled data-id="{{$requisition->id}}" id="perm_req_no" class="form-control activity_name" type="text" name="activity_name" value="{{$requisition->activity_name}}"></td>
                                                        <td scope="col" class="text-center"><input  data-id="{{$requisition->id}}" id="perm_item_name" class="form-control item_name" type="text" name="item_name" value="{{$requisition->item_name}}"></td>
                                                        <td scope="col" class="text-center"><input  data-id="{{$requisition->id}}" style="width:70px;" id="perm_unit_measure" class="form-control unit_measure" type="text" name="unit_measure" value="{{$requisition->unit_measure}}"></td>
                                                        <td scope="col" class="text-center"><input  data-id="{{$requisition->id}}" style="width:55px;" id="perm_quantity" class="form-control quantity" type="text" name="quantity" value="{{$requisition->quantity}}"></td>
                                                        <td scope="col" class="text-center"><input  data-id="{{$requisition->id}}" id="perm_unit_price" class="form-control unit_price" type="text" name="unit_price" value="{{$requisition->unit_price}}"></td>
                                                        <td scope="col" class="text-center">
                                                                <select style="width:130px;"  data-id="{{$requisition->id}}" id="perm_vat" class="form-control vat" name="vat">
                                                                    <option value="{{$requisition->vat}}"  selected>{{$requisition->vat}}</option>
                                                                    <option value="VAT Exclusive">Exclusive</option>
                                                                    <option value="VAT Inclusive">Inclusive</option>
                                                                    <option value="Non VAT">Non VAT</option>
                                                                </select>
                                                        </td>
                                                        <td style="width:85px !important;" scope="col" class="text-center">
                                                                <select data-id="{{$requisition->id}}"  id="perm_account" class="form-control account" name="account_id">
                                                                    <option value="{{$requisition->account}}" selected>{{$requisition->account}}</option>
                                                                    @foreach ($accounts as $account)
                                                                        <option value="{{$account->id}}">{{$account->account_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </td>
                                                        <td scope="col" class="text-center"><input  data-id="{{$requisition->id}}" style="width:140px;" id="perm_description" class="form-control description" type="text" name="description" value="{{$requisition->description}}"></td>
                                                        <td style="width:75px !important;" id="delete-this-row" scope="col" class="text-center">
                                                            <a class="deleting-requisition" data-id="{{$requisition->req_no}}" id="{{$requisition->id}}" href="#">
                                                                <span>
                                                                        <i style="cursor: pointer;" class="material-icons md-10 align-middle mb-1 text-danger">delete_forever</i>
                                                                </span>
                                                            </a>
                                                            <!-- <span data-value="{{$requisition->id}}" style="cursor: pointer;" class="enable-edit-requisition-line">
                                                                Edit
                                                            </span> -->
                                                            <!-- <span style="display:none;" user-id="{{Auth::user()->id}}" data-value="{{$requisition->id}}" style="cursor: pointer;" class="save-requisition-line">
                                                                Save
                                                            </span> -->
                                                        </td>
                                                    </tr>


                                            @endforeach
                                                    <tr class="render-new-requisition">

                                                    </tr>
                                            @elseif(!$requisition_no_budget->isEmpty())

                                            @foreach ($requisition_no_budget as $requisition_no_budget)
                                                <tr>
                                                    <td scope="col" class="text-center">

                                                        <input data-id="{{$requisition_no_budget->id}}" id="no_budget_perm_budget_id" style="width:95px;" class="form-control" type="text" name="budget_id" disabled value="<?php echo 'No Budget'; ?>">
                                                    </td>
                                                    <td scope="col" class="text-center"><input data-id="{{$requisition_no_budget->id}}" disabled style="width:125px;" id="no_budget_perm_item_id" class="form-control" name="item_id" value="<?php echo 'No Budget Line'; ?>"></td>
                                                    <td scope="col" class="text-center"><input data-id="{{$requisition_no_budget->id}}" style="width:90px;" id="no_budget_perm_req_no" class="form-control" type="text" name="req_no" disabled value="{{$requisition_no_budget->req_no}}"></td>
                                                    <td scope="col" class="text-center"><input disabled data-id="{{$requisition_no_budget->id}}" id="no_budget_perm_activity_name" class="form-control activity_name" type="text" name="activity_name" value="{{$requisition_no_budget->activity_name}}"></td>
                                                    <td scope="col" class="text-center"><input  data-id="{{$requisition_no_budget->id}}" id="no_budget_perm_item_name" class="form-control item_name" type="text" name="item_name" value="{{$requisition_no_budget->item_name}}"></td>
                                                    <td scope="col" class="text-center"><input  data-id="{{$requisition_no_budget->id}}" style="width:70px;" id="no_budget_perm_unit_measure" class="form-control unit_measure" type="text" name="unit_measure" value="{{$requisition_no_budget->unit_measure}}"></td>
                                                    <td scope="col" class="text-center"><input  data-id="{{$requisition_no_budget->id}}" style="width:55px;" id="no_budget_perm_quantity" class="form-control quantity" type="text" name="quantity" value="{{$requisition_no_budget->quantity}}"></td>
                                                    <td scope="col" class="text-center"><input  data-id="{{$requisition_no_budget->id}}" id="no_budget_perm_unit_price" class="form-control unit_price" type="text" name="unit_price" value="{{$requisition_no_budget->unit_price}}"></td>
                                                    <td scope="col" class="text-center">
                                                            <select style="width:130px;"  data-id="{{$requisition_no_budget->id}}" id="no_budget_perm_vat" class="form-control vat" name="vat">
                                                                <option value="{{$requisition_no_budget->vat}}"  selected>{{$requisition_no_budget->vat}}</option>
                                                                <option value="VAT Exclusive">Exclusive</option>
                                                                <option value="VAT Inclusive">Inclusive</option>
                                                                <option value="Non VAT">Non VAT</option>
                                                            </select>
                                                    </td>
                                                    <td scope="col" class="text-center">
                                                            <select data-id="{{$requisition_no_budget->id}}"  id="no_budget_perm_account" class="form-control account" name="account_id">
                                                                <option value="{{$requisition_no_budget->account}}" selected>{{$requisition_no_budget->account}}</option>
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{$account->id}}">{{$account->account_name}}</option>
                                                                @endforeach
                                                            </select>
                                                    </td>
                                                    <td scope="col" class="text-center"><input  data-id="{{$requisition_no_budget->id}}" style="width:140px;" id="no_budget_perm_description" class="form-control description" type="text" name="description" value="{{$requisition_no_budget->description}}"></td>
                                                    <td style="width:65px !important;" id="delete-this-row" scope="col" class="text-center">
                                                        <a class="deleting-requisition" style="text-decoration:none;" data-id="{{$requisition_no_budget->req_no}}" id="{{$requisition_no_budget->id}}" href="#">
                                                            <span>
                                                                <i style="cursor: pointer;" class="material-icons md-10 align-middle mb-1 text-danger">delete_forever</i>
                                                            </span>

                                                        </a>
                                                        <!-- <span style="cursor: pointer;" class="enable-edit-requisition-line">
                                                            Edit
                                                        </span> -->
                                                    </td>
                                                </tr>

                                            @endforeach
                                                <tr class="render-new-requisition">

                                                </tr>
                                            @endif
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-center font-weight-bold">Total</td>
                                                <td scope="col" class="text-center" style="background:cornflowerblue">
                                                    <input value="{{number_format(RequisitionsController::getRequisitionTotal($req_no),2)}}" type="text" disabled class="form-control">
                                                </td>
                                                {{-- <td>
                                                    <button type="button" class="btn btn-danger cancel-update">Cancel</button>
                                                </td> --}}
                                                <?php
                                                  $requisition = Requisition::where('req_no', $req_no)->where('status', '!=', 'Deleted')->where('status', '!=', 'Edited')->first();
                                                ?>
                                                <td>
                                                    <a href="#" user-id="{{Auth::user()->id}}" data-id="{{$requisition->id}}" data-value="{{$requisition->req_no}}" class="btn btn-twitter save-requisition-line float-right">Update</a>
                                                    <!-- <a href="#" requisition-number="{{$requisition->req_no}}" class="btn btn-twitter enable-edit-requisition-line float-right">Edit</a> -->
                                                </td>
                                            </tr>

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



        $(document).on('change', '#budget', function() {
            var budget_id = $(this).val();
            if(budget_id) {
                $(".item").show();
                $("#line_description").show();
                $( ".item" ).prop( "disabled", false );
                $( "#line_description" ).prop( "disabled", false );
                $('option', this).not(':eq(0), :selected').remove();

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

        $(document).on('change', '.budget-restrict', function(e) {
            e.preventDefault();
            var budget_id = $(this).val();
            var url = 'budget-restrict/'+ budget_id;

            $.get(url, function(data) {
                console.log(data.result);
                if(data.result != 'undefined'){
                    swal("Warning!", "Make sure you do not use a different budget, otherwise your requisition will not be created.", "warning");
                }else{
                    swal("Opps!", "Cannot submit.", "error");
                }

                // window.location = "create-requisition";
            });
        });

        $(document).on('change', '.item', function(e) {
            var itemId = $(this).val();
            var url = '/get-item-description/'+itemId;
            $.get(url, function(data) {
                console.log(data.result.description);
                $('#line_description').val(data.result.description);

            })
        });

        $(document).on('change', '#item', function(e) {
            var item_id = $(this).val();
            var url = 'create-requisition/'+item_id;
            $.get(url, function(data) {
                console.log(data.result);
            });
        });

        $(document).on('click', '.submit-requisition', function(e) {
            e.preventDefault();


            localStorage.setItem("budget_id", $(this).closest('form').find('select[name=budget_id]').val());
            if (localStorage.getItem("budget_id")) {
              $(this).closest('form').find('select[name=budget_id]').val(localStorage.getItem("budget_id"));
              $("#budget").val(localStorage.getItem("budget_id"));
              console.log(localStorage.getItem("budget_id"));
            }

            var budget_id = $(this).closest('form').find('select[name=budget_id]').val();
            var item_id = $(this).closest('form').find('select[name=item_id]').val();
            var req_no = $(this).closest('form').find('input[name=req_no]').val();
            var activity_name = $(this).closest('form').find('input[name=activity_name]').val();

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
                url: '/submit-single-requisition-row',
                data: $('.requisition-form').serialize()+"&"+$.param({'req_no':req_no,'budget_id':budget_id,'item_id':item_id,'activity_name':activity_name,'item_name2':item_name2, 'unit_measure':unit_measure,'unit_price':unit_price,'quantity':quantity,'vat':vat,'description':description,'account_id':account_id}),
                dataType: "json",
                success: function(data) {
                    console.log(data.result);
                    $('.render-requisition').html(data.result);
                    swal("Good Job", "Requisition line created successfuly.", "success");
                    if (budget_id == localStorage.getItem("budget_id")) {
                        $(this).closest('form').find('select[name=budget_id]').attr('selected', true);
                    }
                    $("#data").find("#activity_name").val('');
                    $("#data").find("#item_name2").val('');
                    $("#data").find("#unit_measure").val('');
                    $("#data").find("#quantity").val('');
                    $("#data").find("#unit_price").val('');
                    $("#data").find("#description").val('');
                },
                error: function(){
                    //alert('opps error occured');
                }
            });

        });

        $(document).on('click', '.refresh-page', function(e) {

            var req_no = $(this).attr("data-value");
            window.location = "edit-requisitions/" + req_no;

        });

        $(document).on('click', '.permanent-requisition', function(e) {
            var req_no = $(this).attr('req-no');
            var url = '/permanent-requisition/'+req_no;
            $.get(url, function(data) {
                console.log(data.result);
                window.location = "submitted-requisitions";
            });
        });

        $(document).on('click', '.add-row', function(e) {
            // $("#data").find('select').each(function(data){


        });

        $(document).on('click', '.delete-row', function(e) {
            e.preventDefault();
            var req_no = attr('data-req').val();
            var req_id = attr('data-id').val();
            alert(req_no);
            // $("table tbody").find('input[name="record"]').each(function(){
            //     if($(this).is(":checked")){
            //       id[i] = $(this).val();
            //     }
            // });
            // $("table tbody tr td").find('.delete-requisition-line').remove();

        });

        $(document).on('click', '.delete-requisition-line', function(e) {
            e.preventDefault();
            var currentRow = $(this);
        	var req_id = $(this).attr('id');
            var url = 'delete-requisition/'+req_id;
            // swal({
            //     title: "Delete",
            //     text: "Are you sure you want to delete this?",
            //     type: "error",
            //     showCancelButton: true,
            //     confirmButtonClass: 'btn-danger waves-effect waves-light',
            //     confirmButtonText: "Delete",
            //     cancelButtonText: "Cancel",
            //     closeOnConfirm: true,
            //     closeOnCancel: true,
            //   }),

              $.get(url, function(data) {
                  console.log(data.result);
                  currentRow.parent().parent().remove();
                  swal("Deleted!", "Your line has been deleted successfuly.", "success");
              });

        });

        $(document).ready(function() {
            $('.preload').fadeOut('3000', function() {
                $('.mydata').fadeIn('2000');
            });
        });

</script>
