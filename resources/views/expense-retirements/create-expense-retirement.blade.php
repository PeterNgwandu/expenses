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
    #activity_name {
        margin-right: 2px;
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
                                    <h4 class="card-title">Expense Retirement
                                        <span class="float-right">
                                            <p class="lead" style="color: #35A45A;">{{ExpenseRetirementController::getTheLatestExpenseRetirementNumber() }}</p>
                                        </span>
                                        <span class="float-right mr-3">
                                            <button style="border-radius:0px;" type="button" user-id="{{Auth::user()->id}}" retirement-no="{{ExpenseRetirementController::getTheLatestExpenseRetirementNumber()}}" class="reset-data btn btn-sm btn-twitter" name="button">Reset</button>
                                        </span>
                                        <span class="float-right mr-1">
                                            <button style="border-radius:0px;" type="button" user-id="{{Auth::user()->id}}" retirement-no="{{ExpenseRetirementController::getTheLatestExpenseRetirementNumber()}}" class="reset-data-back btn btn-sm btn-twitter" name="button">Back</button>
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
                                        <select style="width: 140px;background: #ffffff;border: 1px solid #E5E8E8" id="budget" name="budget_id" class="form-control budget-restrict" data-toogle="tooltip" data-placement="top" title="Select Budget">
                                             <option value="Select Budget" selected disabled>
                                                 Budget
                                             </option>
                                             <option value="0">No Budget</option>
                                             @foreach($budgets as $budget)
                                                 <option value="{{ $budget->id }}" @if (old('budget_id') == $budget->id) selected="selected" @endif>{{ $budget->title }}</option>
                                             @endforeach
                                        </select>
                                        <input id="activity_name" style=" margin-right: 2px;" type="text" name="activity_name" class="form-control activity_name" placeholder="Activity Name" data-toogle="tooltip" data-placement="top" title="Activity Name" value="<?php isset($activity) ? $activity->activity_name : ''; ?>">
                                        <select style="width: 140px; margin-left: -345px;background: #ffffff;border: 1px solid #566573" id="item" name="item_id" class="form-control item" data-toogle="tooltip" data-placement="top" title="Select Budget Line">
                                             <option value="Select Budget" selected disabled>
                                                 Budget Line
                                             </option>
                                             @foreach($items as $item)
                                                <option value="{{$item->id}}">{{$item->item_name}}</option>
                                             @endforeach
                                        </select>

                                        <input id="line_description" type="text" style="width: 280px;" value="" class="form-control" placeholder="Budget Line Description" data-toogle="tooltip" data-placement="top" title="Budget Line Description">
                                        <input type="text" style="width: 140px;background: #ffffff;border: 1px solid #566573" id="supplier" name="supplier_id" class="form-control" placeholder="Supplier" / data-toogle="tooltip" data-placement="top" title="Enter Supplier Name">

                                        <input id="ref_no" style="width: 150px;" type="text" name="ref_no" class="form-control ref_no" placeholder="Ref No." data-toogle="tooltip" data-placement="top" title="Enter Receipt Number">

                                        <input id="purchase_date" style="width: 100px;" type="text" placeholder="Date" name="purchase_date" class="form-control datepicker purchase_date" value="" data-toogle="tooltip" data-placement="top" title="Pick Purchase Date">

                                        <input id="item_name" style="width: 160px; margin-left: 0px" type="text" name="item_name" class="form-control item_name" placeholder="Item" value="" data-toogle="tooltip" data-placement="top" title="Enter Item Purchase">

                                        <input id="unit_measure" style="width: 70px;" type="text" name="unit_measure" class="form-control unit_measure" placeholder="UoM" value="" data-toogle="tooltip" data-placement="top" title="Unit of Measure">

                                        <input id="quantity" style="width: 60px;" type="text" name="quantity" class="form-control quantity" placeholder="Qty" value="" data-toogle="tooltip" data-placement="top" title="Quantity">

                                        <input id="unit_price" style="width: 120px;" type="number" name="unit_price" class="form-control unit_price" placeholder="Price" value="" data-toogle="tooltip" data-placement="top" title="Unit Price">

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

                                        <input id="description" style="width: 280px;" type="text" name="description" class="form-control description" placeholder="Description" data-toogle="tooltip" data-placement="top" title="Description of the Item Purchased">&nbsp;&nbsp;

                                        <!-- <span>
                                           <i class="material-icons submit-expense-retire md-10 align-middle mb-1 text-primary">add_circle</i>
                                           <i class="material-icons delete-row md-10 align-middle mb-1 text-primary">remove_circle</i>
                                        </span> -->
                                        <button style="height:35px;" class="btn  btn-sm btn-twitter submit-expense-retire">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons submit-expense-retire md-10 align-middle mb-1 text-white">add_circle</i>
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
                                        <tbody class="render-expense-retired-items">

                                        </tbody>
                                    </table>
                                    <div class="">
                                        <div class="col-lg-2 float-right" style="margin-right: -15px">
                                           <button type="submit" exp-retire-no="{{ExpenseRetirementController::getTheLatestExpenseRetirementNumber()}}" class="btn permanent-retire float-right btn-twitter mt-3 ml-1">Retire</button>
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
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
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

      $(document).on('change', '.budget-restrict', function(e) {
          e.preventDefault();
          var budget_id = $(this).val();
          var url = '/expense-retirement-budget-restrict/'+ budget_id;

          $.get(url, function(data) {
              console.log(data.result);
              if(data.result != 'undefined'){
                  swal("Warning!", "Make sure you do not use a different budget, otherwise your retirement will not be created.");
              }else{
                  swal("Opps!", "Cannot submit.", "error");
              }
          });
      });


    $(document).on('change', '.item', function(e) {
            var itemId = $(this).val();
            var url = '/get-item-description/'+itemId;
            $.get(url, function(data) {
                console.log(data.result.description);
                $('#line_description').val(data.result.description);

            });
        });

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
            var activity_name = $(this).closest('form').find('input[name=activity_name]').val();
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
                data: $('.retire-form').serialize()+"&"+$.param({'budget_id':budget_id,'item_id':item_id,'supplier_id':supplier_id,'activity_name':activity_name,'ref_no':ref_no, 'item_name2':item_name2, 'purchase_date':purchase_date,'unit_measure':unit_measure,'unit_price':unit_price,'quantity':quantity,'vat':vat,'description':description,'account_id':account_id}),
                dataType: "json",
                success: function(data) {
                    console.log(data.result);
                    $('.render-expense-retired-items').html(data.result);
                    if (budget_id == localStorage.getItem("budget_id")) {
                        $(this).closest('form').find('select[name=budget_id]').attr('selected', true);
                    }
                    if($("#data").find("#activity_name").val() != null){
                        var activity_name = $("#activity_name");
                        activity_name.on('mouseover',function(){
                          if($("#data").find("#activity_name").val() != null){
                            // swal('Alert', 'Please do not change activity name', 'warning');
                            alert('Please do not change activity name', 'warning');
                          }
                        });
                    }
                    if($("#data").find("#budget").val() != null){
                        var budget_id = $("#budget");
                        budget_id.on('mouseover',function(){
                          if($("#data").find("#budget").val() != null){
                            alert('Please do not change budget', 'warning');
                          }
                        });
                    }
                    $(".supplier_id").val("");
                    $(".ref_no").val("");
                    $(".purchase_date").val("");
                    $(".item_name").val("");
                    $(".unit_measure").val("");
                    $(".unit_price").val("");
                    $(".quantity").val("");
                    $(".vat").val("");
                    $(".account").val("");
                    $(".description").val("");
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
