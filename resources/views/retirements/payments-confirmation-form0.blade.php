<?php

use App\User;
use App\Comments\Comment;
use App\StaffLevel\StaffLevel;
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
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
                                <div class="col-lg-12">
                                    <h4 class="card-title">Payment Confirmation Form</h4>

                                    <a data-value="{{$lastRow->req_no}}" href="{{url('confirmation/'.$lastRow->req_no)}}" target="__blank" style="border-radius: 0px !important;" class="btn btn-sm  btn-secondary mt-2">
                                        <span>
                                            <i style="cursor: pointer;" class="material-icons  md-2 align-middle">print</i>
                                        </span>
                                        Print Form
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col col-lg-12">
                              <form class="" action="index.html" method="post">
                                  <div class="col col-lg-8 mt-4 mr-5 ml-5">
                                      <div class="">
                                        <span>
                                          @if($lastRow->status == 'Receive')
                                            <span class="font-weight-bold">RECEIPT No. {{$lastRow->ref_no}} </span>
                                          @elseif($lastRow->status == 'Return')
                                            <span class="font-weight-bold">PAYMENT VOUCHER No. {{$lastRow->ref_no}} </span>
                                          @endif
                                            <span class="float-right font-weight-bold">DATE. {{Carbon::now()->toFormattedDateString()}} </span>
                                        </span>
                                      </div>
                                      <div class="mt-2 font-weight-bold">
                                          @if($lastRow->status == 'Receive')
                                            <span>RECEIVED FROM: {{$lastRow->username}}</span>
                                          @elseif($lastRow->status == 'Return')
                                            <span>PAID TO: {{$lastRow->username}}</span>
                                          @endif
                                      </div>
                                      <div class="mt-4">
                                            <span>Paid via: {{$lastRow->account}}</span>
                                      </div>
                                      <div class="mt-1">
                                            <span>AMOUNT IN Tsh: {{number_format($lastRow->amount_paid,2)}}</span>
                                      </div>
                                      <div class="mt-1">
                                            <?php
                                                $string = 'point Zero Zero';
                                                $amount_in_words = RequisitionsController::convert_number_to_words($lastRow->amount_paid)
                                            ?>
                                            <span>Amount in words: {{str_replace($string, 'Tsh', $amount_in_words)}}</span>
                                      </div>

                                      <div class="mt-4 font-weight-bold">
                                            <span>DETAILS OF REQISITION </span>
                                      </div>
                                      <div class="mt-1">
                                            <span>Requisition No: <span class="font-weight-bold">{{$lastRow->req_no}}</span></span>
                                      </div>
                                      <div class="mt-1">
                                            <span>Comments: {{$lastRow->comment}}</span>
                                      </div>
                                      <table class="table table-bordered mt-3">
                                          <thead style="display:none;">
                                              <th></th>
                                              <th></th>
                                          </thead>
                                          <body>
                                              <td>
                                                <p>Received By: {{$lastRow->username}}</p>
                                                <p class="mt-2">Sign: ......................................................</p>
                                              </td>
                                              <td>
                                                <p>Paid By: {{$lastRow->cash_collector}}</p>
                                                <p class="mt-2">Sign: ...........................................................</p>
                                              </td>
                                          </body>
                                      </table>
                                  </div>
                              </form>
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

    $(document).ready(function() {
        $('.preload').fadeOut('3000', function() {
            $('.mydata').fadeIn('2000');
        });
    });

    $("#approveBtn").attr("href","Process Payment");

    //$("#approveBtn").html('Process Payment');

</script>
