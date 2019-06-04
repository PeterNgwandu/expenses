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

?>
<!DOCTYPE html>
<html>
<head>
	<title>Payment Confirmation Form</title>
	<!-- App CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{url('assets/bootstrap/scss/bootstrap.scss')}}">
</head>
<body style="margin:0px !important;">
    <div class="float-right">
        <img style="height:90px;width:100" src="{{public_path('assets/images/fastpay.jpg')}}" alt="FastPay Solutions Logo">
        <h5 class="font-weight-bold">FastPay Solutions Limited</h5>
        <p>{{Carbon::now()->toFormattedDateString()}}</p>
    </div><br><br><br>
    <div class="container">


        <div class="row">
            <div class="col-lg-12">
                    <div class="row">
                      <div class="col col-lg-12">
                          <form class="" action="index.html" method="post">
                              <div class="col col-lg-8 mt-4">
                                  <div class="">
                                    @if($lastRow->status == 'Receive')
                                      <span class="font-weight-bold">RECEIPT No. {{$lastRow->ref_no}} </span>
                                    @elseif($lastRow->status == 'Return')
                                      <span class="font-weight-bold">VOUCHER No. {{$lastRow->ref_no}} </span>
                                    @endif
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
                                        <span>DETAILS OF REQISITION  </span>
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
                                            <p>Paid By: {{$lastRow->username}}</p>
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
</body>
</html>
