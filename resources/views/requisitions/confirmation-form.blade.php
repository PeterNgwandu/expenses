<?php

use App\User;
use App\Comments\Comment;
use App\StaffLevel\StaffLevel;
use Illuminate\Support\Carbon;
use App\Requisition\Requisition;
use App\Http\Controllers\Requisitions\RequisitionsController;

$stafflevels = StaffLevel::all();

$hod = $stafflevels[0]->id;
$ceo = $stafflevels[1]->id;
$supervisor = $stafflevels[2]->id;
$normalStaff = $stafflevels[3]->id;
$financeDirector = $stafflevels[4]->id;


$requisition = Requisition::where('req_no', $req_no)->where('status', '!=', 'Deleted')->where('requisitions.status', '!=', 'Edited')->get();

$approver = StaffLevel::join('requisitions','staff_levels.id', 'requisitions.approver_id')
                           ->join('users','staff_levels.id','users.stafflevel_id')
                           ->select('users.username as approver_name')
                           ->where('requisitions.req_no', $req_no)
                           ->where('users.username', '!=', 'Admin')
                           ->first();

$req = Requisition::where('req_no', $req_no)->where('status', '!=', 'Deleted')->where('requisitions.status', '!=', 'Edited')->where('budget_id',0)->get();

$user = User::where('users.id', Requisition::where('req_no', $req_no)->distinct()->pluck('user_id'))
        ->join('departments','users.department_id','departments.id')
        ->select('users.*','departments.name as department')
        ->first();

$requisitions = Requisition::where('req_no', $req_no)
                          ->join('budgets','requisitions.budget_id','budgets.id')
                          ->join('items','requisitions.item_id','items.id')
                          ->select('requisitions.*','budgets.title as budget','items.item_name as item')
                          ->where('requisitions.status', '!=', 'Deleted')
                          ->where('requisitions.status', '!=', 'Edited')
                          ->get();

$comments = Comment::where('req_no', $req_no)->join('users','comments.user_id','users.id')->select('comments.*', 'users.username as username')->get();

$requisition = Requisition::where('req_no', $req_no)->where('status', '!=', 'Deleted')->where('requisitions.status', '!=', 'Edited')->first();

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
                                    <h4 class="card-title">Payment Confirmatio Form</h4>

                                    <a data-value="{{$req_no}}" href="{{url('confirmation/'.$requisition->req_no)}}" target="__blank" style="border-radius: 0px !important;" class="btn btn-sm print-confirmation-form btn-secondary mt-2">
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
                                            <span class="font-weight-bold">PAYMENT VOUCHER No. {{RequisitionsController::generateVoucherNo()}} </span>
                                            <span class="float-right font-weight-bold">DATE. {{Carbon::now()->toFormattedDateString()}} </span>
                                        </span>
                                      </div>
                                      <div class="mt-2 font-weight-bold">
                                            <span>PAID TO: {{$paid_to->username}}</span>
                                      </div>
                                      <div class="mt-4">
                                            <span>Paid via: </span>
                                      </div>
                                      <div class="mt-1">
                                            <span>AMOUNT IN Tsh: </span>
                                      </div>
                                      <div class="mt-1">
                                            <span>Amount in words: </span>
                                      </div>
                                      <div class="mt-4 font-weight-bold">
                                            <span>DETAILS OF REQISITION / EXPENSE RETIREMENT: </span>
                                      </div>
                                      <div class="mt-1">
                                            <span>Requisition No: <span class="font-weight-bold">{{$req_no}}</span></span>
                                      </div>
                                      <div class="mt-1">
                                            <span>Expense Retirement No: </span>
                                      </div>
                                      <div class="mt-1">
                                            <span>Comments: </span>
                                      </div>
                                      <table class="table table-bordered mt-3">
                                          <thead style="display:none;">
                                              <th></th>
                                              <th></th>
                                          </thead>
                                          <body>
                                              <td>
                                                <p>Received By: {{$paid_to->username}}</p>
                                                <p class="mt-2">Sign: ......................................................</p>
                                              </td>
                                              <td>
                                                <p>Paid By: {{$payer_name->username}}</p>
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
