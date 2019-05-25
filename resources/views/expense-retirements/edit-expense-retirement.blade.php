<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Retirements\RetirementController;
use App\Http\Controllers\Requisitions\RequisitionsController;
use App\Http\Controllers\ExpenseRetirements\ExpenseRetirementController;

?>

@extends('layout.app')
<style type="text/css">
    .material-icons {
        cursor: pointer;
    }
    .expense-retirement {
        max-width: 96% !important;
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
    #exp-ret-no {
        background: #35A45A;
        border-radius: 3px;
        margin-right: 2px;
    }
    #budget {
        margin-top: -140px;
        margin-bottom: 10px;
        background: #E5E8E8;
}
    #item_name {
        margin-left: -140px;
}
input,
input::-webkit-input-placeholder {
    font-size: 12px;
    line-height: 3;
}
select,option {
   font-size: 13px;
}
</style>
@section('content')
 <div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable" style="margin-top: 30px">

        <div class="container expense-retirement">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card card-earnings">
                        <div class="card-header bg-faded" style="height: 80px;">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Edit Expense Retirement
                                        <span class="float-right">
                                            <p class="lead" style="color: #35A45A;">{{$ret_no}}</p>
                                        </span>
                                        <!-- <span class="float-right mr-3">
                                            <button style="border-radius:0px;" type="button" user-id="{{Auth::user()->id}}" retirement-no="{{ExpenseRetirementController::getTheLatestExpenseRetirementNumber()}}" class="reset-data btn btn-sm btn-twitter" name="button">Reset</button>
                                        </span> -->
                                        <span class="float-right mr-1">
                                            <button style="border-radius:0px;" type="button" user-id="{{Auth::user()->id}}" retirement-no="{{$ret_no}}" class="reset-data-back btn btn-sm btn-twitter" name="button">Back</button>
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                                <!-- <p>
                                <span>
                                   <i class="material-icons md-10 align-middle mb-1 text-info">info</i>
                                </span>
                                Budget Is Optional
                                </p> -->
                                <div class="form-inline" >
                                    <form style="margin-top: 10px;" class="form-inline data retire-form" id="data">
                                        @csrf
                                        <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                                        <input type="hidden" name="ret_no" value="{{ExpenseRetirementController::getTheLatestExpenseRetirementNumber() }}">
                                        <select style="width: 140px;background: #ffffff;border: 1px solid #E5E8E8" id="budget" name="budget_id" class="form-control" data-toogle="tooltip" data-placement="top" title="Select Budget">
                                             <option value="Select Budget" selected disabled>
                                                 Budget
                                             </option>
                                             <option value="0">No Budget</option>
                                             @foreach($budgets as $budget)
                                                 <option value="{{ $budget->id }}" @if (old('budget_id') == $budget->id) selected="selected" @endif>{{ $budget->title }}</option>
                                             @endforeach
                                        </select>
                                        <select style="width: 140px; margin-left: -140px;background: #ffffff;border: 1px solid #566573" id="item" name="item_id" class="form-control item" data-toogle="tooltip" data-placement="top" title="Select Budget Line">
                                             <option value="Select Budget" selected disabled>
                                                 Budget Line
                                             </option>
                                             @foreach($items as $item)
                                                <option value="{{$item->id}}">{{$item->item_name}}</option>
                                             @endforeach
                                        </select>
                                        <input id="line_description" type="text" style="width: 280px;" value="" class="form-control" placeholder="Budget Line Description" data-toogle="tooltip" data-placement="top" title="Budget Line Description">
                                        <input type="text" style="width: 140px;background: #ffffff;border: 1px solid #566573" id="supplier" name="supplier_id" class="form-control" placeholder="Supplier" / data-toogle="tooltip" data-placement="top" title="Enter Supplier Name">

                                        <input style="width: 150px;" type="text" name="ref_no" class="form-control ref_no" placeholder="Ref No." data-toogle="tooltip" data-placement="top" title="Enter Receipt Number">

                                        <input style="width: 100px;" type="text" placeholder="Date" name="purchase_date" class="form-control datepicker purchase_date" value="" data-toogle="tooltip" data-placement="top" title="Pick Purchase Date">

                                        <input id="item_name" style="width: 160px; margin-left: 0px" type="text" name="item_name" class="form-control item_name" placeholder="Item" value="" data-toogle="tooltip" data-placement="top" title="Enter Item Purchase">

                                        <input style="width: 70px;" type="text" name="unit_measure" class="form-control unit_measure" placeholder="UoM" value="" data-toogle="tooltip" data-placement="top" title="Unit of Measure">

                                        <input style="width: 60px;" type="text" name="quantity" class="form-control quantity" placeholder="Qty" value="" data-toogle="tooltip" data-placement="top" title="Quantity">

                                        <input style="width: 120px;" type="number" name="unit_price" class="form-control unit_price" placeholder="Price" value="" data-toogle="tooltip" data-placement="top" title="Unit Price">

                                        <select style="width: 125px;" name="vat" value="" class="form-control vat" data-toogle="tooltip" data-placement="top" title="Select VAT Options">
                                            <option value="VAT_Options" selected disabled >VAT</option>
                                            <option value="VAT Exclusive">VAT Exclusive</option>
                                            <option value="VAT Inclusive">VAT Inclusive</option>
                                            <option value="Non VAT">Non VAT</option>
                                        </select>

                                        <select id="account" style="width: 105px;" name="account_id" class="form-control accounts" data-toogle="tooltip" data-placement="top" title="Select Account">
                                            <option value="Account" selected disabled>Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}">{{$account->account_name}}</option>
                                            @endforeach
                                        </select>

                                        <input style="width: 280px;" type="text" name="description" class="form-control description" placeholder="Description" data-toogle="tooltip" data-placement="top" title="Description of the Item Purchased">&nbsp;&nbsp;

                                        <!-- <span>
                                           <i class="material-icons submit-expense-retire md-10 align-middle mb-1 text-primary">add_circle</i>
                                           <i class="material-icons delete-row md-10 align-middle mb-1 text-primary">remove_circle</i>
                                        </span> -->
                                        <button style="height:35px;" ret-no="{{$ret_no}}" class="btn  btn-sm btn-twitter submit-edit-expense-retire">
                                            <span>
                                                <i style="cursor: pointer;" ret-no="{{$ret_no}}" class="material-icons submit-edit-expense-retire md-10 align-middle mb-1 text-white">add_circle</i>
                                                Add Line
                                             </span>
                                        </button>
                                        <br>
                                        <hr><hr>
                                    </form>

                                </div>

                                    <table class="table table-sm mb-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" class="text-center">Budget</th>
                                                <th scope="col" class="text-center">Budget Line</th>
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
                                                <th scope="col" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                  @foreach($expense_retirement as $retirement)
                                                      <tr>
                                                          <td scope="col" class="text-center"><input id="budget_id" disabled type="text" class="form-control budget_id" name="budget_id" value="<?php if($retirement->budget_id == 0){echo 'No Budget';}else{echo $retirement->budget;} ?>"></td>
                                                          <td scope="col" class="text-center"><input id="item_id" disabled type="text" class="form-control item_id" name="item_id" value="<?php if($retirement->item_id == null){echo 'No Budget Line';}else{echo $retirement->item;} ?>"></td>
                                                          <td scope="col" class="text-center"><input id="supplier_id" data-id="{{$retirement->id}}" type="text" class="form-control supplier_id" name="supplier_id" value="{{$retirement->supplier_id}}"></td>
                                                          <td scope="col" class="text-center"><input id="ref_no" data-id="{{$retirement->id}}" type="text" class="form-control ref_no" name="ref_no" value="{{$retirement->ref_no}}"></td>
                                                          <td scope="col" class="text-center"><input id="purchase_date" data-id="{{$retirement->id}}" type="text" class="form-control datepicker purchase_date" name="purchase_date" value="{{$retirement->purchase_date}}"></td>
                                                          <td scope="col" class="text-center"><input id="item_namme" data-id="{{$retirement->id}}" type="text" name="item_name" class="form-control item_namme" value="{{$retirement->item_name}}"></td>
                                                          <td scope="col" class="text-center"><input id="unit_measure" data-id="{{$retirement->id}}" type="text" class="form-control unit_measure" name="unit_measure" value="{{$retirement->unit_measure}}"></td>
                                                          <td scope="col" class="text-center"><input id="quantity" data-id="{{$retirement->id}}" type="text" class="form-control quantity" name="quantity" value="{{$retirement->quantity}}"></td>
                                                          <td scope="col" class="text-center"><input id="unit_price" data-id="{{$retirement->id}}" type="text" class="form-control unit_price" name="unit_price" value="{{$retirement->unit_price}}"></td>
                                                          <td scope="col" class="text-center">
                                                            <select data-id="{{$retirement->id}}" id="vat" class="form-control vat" name="vat">
                                                                <option selected value="{{$retirement->vat}}">{{$retirement->vat}}</option>
                                                                <option value="VAT Inclusive">VAT Inclusive</option>
                                                                <option value="VAT Exclusive">VAT Exclusive</option>
                                                                <option value="Non VAT">Non VAT</option>
                                                            </select>
                                                        </td>
                                                          <td scope="col" class="text-center">
                                                            <select data-id="{{$retirement->id}}" id="account" type="text" class="form-control account" name="account">
                                                                <option  value="{{$retirement->id}}" selected>{{$retirement->account}}</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{$account->id}}">{{$account->account_name}}</option>
                                                                @endforeach
                                                            </select>
                                                          </td>
                                                          <td scope="col" class="text-center"><input id="description" data-id="{{$retirement->id}}" type="text" class="form-control description" name="description" value="{{$retirement->description}}"></td>
                                                          <td scope="col" class="text-center">
                                                      			<span>
                                                      					<i style="cursor: pointer;" retirement-no="{{$ret_no}}" data-id="{{$retirement->id}}" class="material-icons delete-expense-retirement-line md-10 align-middle mb-1 text-danger">delete_forever</i>
                                                      			 </span>
                                                      		</td>
                                                      </tr>
                                                  @endforeach
                                            </tbody>
                                            <tbody class="render-edit-expense-retired-items">

                                            </tbody>
                                    </table>
                                    <div class="">
                                        <div class="col-lg-2 float-right" style="margin-right: -15px">
                                           <button expense-retire-no="{{$ret_no}}" user-id="{{Auth::user()->id}}" class="btn update-expense-retirement float-right btn-twitter mt-3 ml-1">Update</button>
                                        </div>
                                    </div>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>

  </script>
<script type="text/javascript">

    var data = [];
    var coun = 0 ;

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
            if(budget_id == 0){
                $(".item").hide();
                $("#line_description").hide();
                $( ".item" ).prop( "disabled", true );
                $( "#line_description" ).prop( "disabled", true );
            }
        });

    $(document).on('change', '.item', function(e) {
            var itemId = $(this).val();
            var url = '/get-item-description/'+itemId;
            $.get(url, function(data) {
                console.log(data.result.description);
                $('#line_description').val(data.result.description);

            })
        });

    // $(document).on('click', '.update-expense-retirement', function(e) {
    //     e.preventDefault();
    //     var user_id = $(this).attr('user-id');
    //     var exp_retire_no = $(this).attr('expense-retire-no');
    //     var url = '/bring-expense-retirement-to-permanent-table/' + user_id + '/' + exp_retire_no;
    //     $.get(url, function(data) {
    //         console.log(data.result);
    //         window.location = '/expense_retirements/' + exp_retire_no;
    //     });
    // });

    $(document).on('click', '.submit-expense-retire', function(e) {
            e.preventDefault();

            localStorage.setItem("budget_id", $(this).closest('form').find('select[name=budget_id]').val());
            if (localStorage.getItem("budget_id")) {
              $(this).closest('form').find('select[name=budget_id]').val(localStorage.getItem("budget_id"));
              $("#budget").val(localStorage.getItem("budget_id"));
              console.log(localStorage.getItem("budget_id"));
            }

            var budget_id = $(this).closest('form').find('select[name=budget_id]').val();
            var item_id = $(this).closest('form').find('select[name=item_id]').val();
            var supplier_id = $(this).closest('form').find('input[name=supplier_id]').val();
            var ref_no = $(this).closest('form').find('input[name=ref_no]').val();
            var purchase_date = $(this).closest('form').find('input[name=purchase_date]').val();
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
                url: '/submit-single-expense-retire-row',
                data: $('.retire-form').serialize()+"&"+$.param({'budget_id':budget_id,'item_id':item_id,'supplier_id':supplier_id,'ref_no':ref_no, 'item_name2':item_name2, 'purchase_date':purchase_date,'unit_measure':unit_measure,'unit_price':unit_price,'quantity':quantity,'vat':vat,'description':description,'account_id':account_id}),
                dataType: "json",
                success: function(data) {
                    console.log(data.result);
                    $('.render-expense-retired-items').html(data.result);
                    if (budget_id == localStorage.getItem("budget_id")) {
                        $(this).closest('form').find('select[name=budget_id]').attr('selected', true);
                    }
                },
                error: function(){
                    //alert('opps error occured');
                }
            });

    });

    $(document).on('click', '.permanent-retire', function(e) {
        var exp_retire_no = $(this).attr('exp-retire-no');
        var url = '/expense-permanent-retire/'+exp_retire_no;
        $.get(url, function(data) {
            console.log(data.result);
            window.location = "/expense_retirements/manage";
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

     $(document).ready(function() {
            $('.preload').fadeOut('3000', function() {
                $('.mydata').fadeIn('2000');
            });
        });

</script>
