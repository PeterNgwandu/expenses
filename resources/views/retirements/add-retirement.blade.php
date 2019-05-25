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
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable" style="margin-top: -20px">

        <div class="container expense-retirement">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Create Retirement

                                        <span class="float-right">
                                            <p class="lead" style="color: #35A45A;">{{$ret_no}}

                                            </p>
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="card card-body bg-light ">
                                            <table style="width: 345px;" class="col-lg-6 table table-sm table-striped table-bordered">
                                                @if(!$submitted_requisitions->isEmpty())
                                                <thead>
                                                <tr>
                                                    <th>Requisition Summary</th>
                                                </tr>
                                                <tr>
                                                    <th  scope="col" class="text-center">Requisition No.</th>
                                                    <th scope="col" class="text-center">Budget</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                       <td scope="col" class="text-center">{{$submitted_requisitions[0]->req_no}}</td>
                                                       <td scope="col" class="text-center">{{$submitted_requisitions[0]->budget}}</td>
                                                    </tr>
                                                </tbody>
                                                @endif
                                            </table>
                                            <table class="table table-sm table-striped table-bordered">
                                                @if(!$submitted_requisitions->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>Requisition Total Summary</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Budget Line</th>
                                                        <th scope="col" class="text-center">Item Name</th>
                                                        <th scope="col" class="text-center">Unit of Measure</th>
                                                        <th scope="col" class="text-center">Quantity</th>
                                                        <th scope="col" class="text-center">Gross Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($submitted_requisitions as $requisition)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$requisition->item}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->item_name}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->unit_measure}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->quantity}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->gross_amount}}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td scope="col" class="text-center">Total</td>
                                                        <td style="background: #FFF6F4;" scope="col" class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;
                                                            {{RequisitionsController::getRequisitionTotal($requisition->req_no)}} /=
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                @endif
                                                @if($submitted_requisitions->isEmpty())
                                                <thead>
                                                    <tr>
                                                        <th>Requisition Summary
                                                            <span class="float-right">
                                                                <h5 class="text-success">{{$submitted_paid_no_budget[0]->req_no}}
                                                                </h5>

                                                            </span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Item Name</th>
                                                        <th scope="col" class="text-center">Unit of Measure</th>
                                                        <th scope="col" class="text-center">Quantity</th>
                                                        <th scope="col" class="text-center">Unit Price</th>
                                                        <th scope="col" class="text-center">VAT Amount</th>
                                                        <th scope="col" class="text-center">Gross Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($submitted_paid_no_budget as $requisition)
                                                        <tr>
                                                           <td scope="col" class="text-center">{{$requisition->item_name}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->unit_measure}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->quantity}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->unit_price}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->vat_amount}}</td>
                                                           <td scope="col" class="text-center">{{$requisition->gross_amount}}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td scope="col" class="text-center">Total</td>
                                                        <td style="background: #FFF6F4;" scope="col" class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;
                                                            {{RequisitionsController::getRequisitionTotal($requisition->req_no)}} /=
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                @endif

                                            </table>
                                            <hr>
                                            @if(!$submitted_requisitions->isEmpty())

                                <div class="form-inline">
                                    <form class="form-inline data retire-form" id="data">
                                        @csrf
                                        <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                                        <input type="hidden" name="req_no" value="{{$submitted_requisitions[0]->req_no}}">
                                        <input type="hidden" name="ret_no" value="{{$ret_no}}">
                                        <select required style="width: 140px;background: #ffffff;border: 1px solid #566573" name="serial_no" class="form-control serial_no" data-toogle="tooltip" data-placement="top" title="Select Requisition Serial Number">
                                            <option value="Serial_No" selected disabled="">Serial No.</option>
                                            <?php $counter = 1; ?>
                                            @foreach($submitted_requisitions as $requisition)
                                                <option value="{{$requisition->serial_no}}">
                                                  <?php

                                                     if($requisition->count() != 0) {echo '<ol><li>'.$counter++.'</li></ol>';}?>
                                                </option>
                                            @endforeach
                                        </select>
                                        <input required type="text" style="width: 140px;background: #ffffff;border: 1px solid #566573" id="supplier" name="supplier_id" class="form-control" placeholder="Supplier" data-toogle="tooltip" data-placement="top" title="Enter Supplier Name">

                                        <input required style="width: 100px;" type="text" name="ref_no" class="form-control ref_no" placeholder="Ref No." data-toogle="tooltip" data-placement="top" title="Enter Receipt Number">

                                        <input required style="width: 100px;" type="text" placeholder="Date" name="purchase_date" class="form-control datepicker purchase_date" value="" data-toogle="tooltip" data-placement="top" title="Pick Purchase Date">

                                        <input required id="item_name" style="width: 100px;" type="text" name="item_name" class="form-control item_name" placeholder="Item" value="" data-toogle="tooltip" data-placement="top" title="Enter Item Purchased">

                                        <input required style="width: 70px;" type="text" name="unit_measure" class="form-control unit_measure" placeholder="UoM" value="" data-toogle="tooltip" data-placement="top" title="Unit of Measure">

                                        <input required style="width: 60px;" type="text" name="quantity" class="form-control quantity" placeholder="Qty" value="" data-toogle="tooltip" data-placement="top" title="Quantity">

                                        <input required style="width: 120px;" type="number" name="unit_price" class="form-control unit_price" placeholder="Price" value="" data-toogle="tooltip" data-placement="top" title="Unit Price">

                                        <select required style="width: 85px;" name="vat" value="" class="form-control vat" data-toogle="tooltip" data-placement="top" title="Select VAT Options">
                                            <option value="VAT_Options" selected disabled>VAT</option>
                                            <option value="VAT Exclusive">VAT Exclusive</option>
                                            <option value="VAT Inclusive">VAT Inclusive</option>
                                            <option value="Non VAT">Non VAT</option>
                                        </select>

                                        <select required id="account" style="width: 125px;" name="account_id" class="form-control accounts" data-toogle="tooltip" data-placement="top" title="Select Account">
                                            <option value="VAT Options" selected disabled>Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}">{{$account->account_name}}</option>
                                            @endforeach
                                        </select>

                                        <input required style="width: 280px;" type="text" name="description" class="form-control description" placeholder="Description" data-toogle="tooltip" data-placement="top" title="Description of the Item Purchased">

                                        <span>&nbsp;
                                           <!-- <i class="material-icons submit-retire md-10 align-middle mb-1 text-primary">add_circle</i> -->
                                           {{-- <i class="material-icons delete-row md-10 align-middle mb-1 text-primary">remove</i>  --}}
                                        </span>
                                        <button style="height:35px;" class="btn  btn-sm btn-twitter add-retirement">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons add-retirement md-10 align-middle mb-1 text-white">add_circle</i>
                                                Add Line
                                                <!-- <i style="cursor: pointer;" class="material-icons delete-row md-10 align-middle mb-1 text-primary">remove_circle</i> -->
                                             </span>
                                        </button>
                                        <!-- <button type="submit" class="btn float-right btn-outline-primary mt-3 ml-1">Retire</button> -->
                                        <br>
                                        <hr><hr>
                                    </form>

                                </div>
                                @endif
                                @if($submitted_requisitions->isEmpty())

                                <div class="form-inline">
                                    <form class="form-inline data retire-form" id="data">
                                        @csrf
                                        <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                                        <input type="hidden" name="req_no" value="{{$submitted_paid_no_budget[0]->req_no}}">
                                        <input type="hidden" name="ret_no" value="{{RetirementController::getTheLatestRetirementNumber() }}">
                                        <select required style="width: 140px;background: #ffffff;border: 1px solid #566573" name="serial_no" class="form-control serial_no">
                                            <option value="Serial_No" selected disabled="">Serial No.</option>
                                            @foreach($submitted_paid_no_budget as $requsition)
                                                <option value="{{$requisition->serial_no}}">{{$requisition->serial_no}}</option>
                                            @endforeach
                                        </select>
                                        <input required type="text" style="width: 140px;background: #ffffff;border: 1px solid #566573" id="supplier" name="supplier_id" class="form-control" placeholder="Supplier" />

                                        <input required style="width: 100px;" type="text" name="ref_no" class="form-control ref_no" placeholder="Ref No.">

                                        <input required style="width: 100px;" type="text" placeholder="Date" name="purchase_date" class="form-control datepicker purchase_date" value="">

                                        <input required id="item_name" style="width: 100px;" type="text" name="item_name" class="form-control item_name" placeholder="Item" value="">

                                        <input required style="width: 70px;" type="text" name="unit_measure" class="form-control unit_measure" placeholder="UoM" value="">

                                        <input required style="width: 60px;" type="text" name="quantity" class="form-control quantity" placeholder="Qty" value="">

                                        <input required style="width: 120px;" type="number" name="unit_price" class="form-control unit_price" placeholder="Price" value="">

                                        <select required style="width: 85px;" name="vat" value="" class="form-control vat">
                                            <option value="VAT_Options" selected disabled>VAT</option>
                                            <option value="VAT Exclusive">VAT Exclusive</option>
                                            <option value="VAT Inclusive">VAT Inclusive</option>
                                            <option value="Non VAT">Non VAT</option>
                                        </select>

                                        <select required id="account" style="width: 125px;" name="account_id" class="form-control accounts">
                                            <option value="VAT Options" selected disabled>Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}">{{$account->account_name}}</option>
                                            @endforeach
                                        </select>

                                        <input required style="width: 280px;" type="text" name="description" class="form-control description" placeholder="Description">

                                        <span>
                                           <i class="material-icons add-retirement md-10 align-middle mb-1 text-primary">add_circle</i>
                                           {{-- <i class="material-icons delete-row md-10 align-middle mb-1 text-primary">remove</i>  --}}
                                        </span>

                                        <!-- <button type="submit" class="btn float-right btn-outline-primary mt-3 ml-1">Retire</button> -->
                                        <br>
                                        <hr><hr>
                                    </form>

                                </div>
                                @endif

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
                                        <tbody class="render-retired-items">

                                        </tbody>
                                    </table>
                                    <div class="">
                                        <div class="col-lg-2 float-right" style="margin-right: -15px">
                                           <button type="submit" retire-no="{{$ret_no}}" class="btn permanent-retire float-right btn-outline-primary mt-3 ml-1">Retire</button>
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
<script type="text/javascript">
    var data = [];
    var coun = 0 ;

    $(document).on('click', '.add-retirement', function(e) {
            e.preventDefault();
            var serial_no = $(this).closest('form').find('select[name=serial_no]').val();
            var supplier_id = $(this).closest('form').find('input[name=supplier_id]').val();
            var ref_no = $(this).closest('form').find('input[name=ref_no]').val();
            var purchase_date = $(this).closest('form').find('input[name=purchase_date]').val();
            var item_name2 = $(this).closest('form').find('input[name=item_name]').val();
            var unit_measure = $(this).closest('form').find('input[name=unit_measure]').val();
            var unit_price = $(this).closest('form').find('input[name=unit_price]').val();
            var quantity = $(this).closest('form').find('input[name=quantity]').val();
            var vat = $(this).closest('form').find('select[name=vat]').val();
            var description = $(this).closest('form').find('input[name=description]').val();
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $.ajax({
                type: "POST",
                url: '/add-retirement-row',
                data: $('.retire-form').serialize()+"&"+$.param({'serial_no':serial_no,'ref_no':ref_no, 'item_name2':item_name2, 'purchase_date':purchase_date,'unit_measure':unit_measure,'unit_price':unit_price,'quantity':quantity,'vat':vat,'description':description}),
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
            url2 = '/retirements';
            window.location = url2;
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

    $(function()
{
    $('.date-pick')
            .datepicker({createButton: false})
            .bind('click',
                    function()
                    {
                            $(this).dpDisplay();
                            this.blur();
                            return false;
                    }
            );
    $('#bl').dpSetPosition($.dpConst.POS_BOTTOM, $.dpConst.POS_LEFT);
});

</script>
