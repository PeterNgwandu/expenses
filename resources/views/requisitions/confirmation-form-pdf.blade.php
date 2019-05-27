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
<!DOCTYPE html>
<html>
<head>
	<title>Payment Confirmation Form</title>
	<!-- App CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{url('assets/bootstrap/scss/bootstrap.scss')}}">
</head>
<body>
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
                                    <span>
                                        <span class="font-weight-bold">PAYMENT VOUCHER No. {{RequisitionsController::generateVoucherNo()}} </span>
                                        <!-- <span class="float-right font-weight-bold">DATE. {{Carbon::now()->toFormattedDateString()}} </span> -->
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
</body>
</html>
