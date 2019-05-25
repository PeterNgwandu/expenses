<?php

use App\User;
use App\StaffLevel\StaffLevel;
use Illuminate\Support\Facades\Auth;
use App\Comments\ExpenseRetirementComment;
use App\ExpenseRetirement\ExpenseRetirement;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Requisitions\RequisitionsController;
use App\Http\Controllers\ExpenseRetirements\ExpenseRetirementController;

$uid = ExpenseRetirement::where('ret_no',$ret_no)->get();

$staffLevel = StaffLevel::all();

$user = User::where('users.id', $uid[0]->user_id)
        ->join('departments','users.department_id','departments.id')
        ->select('users.*','departments.name as department')
        ->first();

$comments = ExpenseRetirementComment::where('ret_no', $ret_no)->join('users','expense_retirement_comments.user_id','users.id')->select('expense_retirement_comments.*', 'users.username as username')->get();

?>

@extends('layout.app')
<style type="text/css">
    #flash {
        position: absolute;
        bottom: 20px;
        right: 20px;
        z-index: 10;
    }
    .expense-retirement {
        max-width: 96% !important;
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
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container expense-retirement">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Manage Expense Retirements
                                    </h4>
                                    <span class="float-right">
                                        <p class="lead" style="color: #35A45A;">

                                        </p>
                                    </span>
                                    @if(Auth::user()->id == $expense_summary->user_id && $expense_summary->status != 'Confirmed')
                                      @if($expense_summary->status != 'Approved By Supervisor' || $expense_summary->status != 'Approved By HOD' || $expense_summary->status != 'Approved By Finance' || $expense_summary->status != 'Confirmed' || $expense_summary->status != 'Rejected By Supervisor' || $expense_summary->status != 'Rejected By HOD' || $expense_summary->status != 'Rejected By Finance' || $expense_summary->status != 'Rejected By Supervisor' || $expense_summary->status != 'Rejected By CEO')
                                        <a href="{{url('edit-expense-retirement-line/'.$expense_summary->ret_no)}}" ret-number="{{$expense_summary->ret_no}}" style="border-radius: 0px !important;" class="btn enable-edit-expense-retirement-line btn-sm btn-success mt-2">
                                            <span>
                                                <i style="cursor: pointer;" class="material-icons enable-edit-expense-retirement-line md-2 align-middle">edit</i>
                                            </span>
                                            Edit
                                        </a>
                                      @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                                <div class="col-4 ml-3">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8 mt-2">
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Retiree Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Username : {{$user->username}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email : {{$user->email}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone : {{$user->phone}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Department : {{$user->department}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-6 mt-2">
                                            @if(!$expense_retirements->isEmpty())
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Expense Retirement Details</th>
                                                    </tr>
                                                    <tr>
                                                        <th  scope="col" class="text-center">Expense Retirment No.</th>
                                                        <th scope="col" class="text-center">Budget</th>
                                                        <th scope="col" class="text-center">Status</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td scope="col" class="text-center">{{$expense_summary->ret_no}}</td>
                                                        <td scope="col" class="text-center">{{$expense_summary->budget}}</td>
                                                        <td scope="col" class="text-center">{{$expense_summary->status}}</td>


                                                    </tr>

                                                </tbody>
                                            </table>
                                            @endif

                                        </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-6" mt-2>
                                         @if($expense_retirements->isEmpty())
                                                <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Expense Retirement Details</th>
                                                    </tr>
                                                    <tr>
                                                        <th  scope="col" class="text-center">Expense Retirment No.</th>
                                                        <th scope="col" class="text-center">Status</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td scope="col" class="text-center">{{$ex_retirement_no_budget[0]->ret_no}}</td>
                                                        <td scope="col" class="text-center">{{$ex_retirement_no_budget[0]->status}}</td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-12 mt-2">
                                            @if(!$expense_retirements->isEmpty())
                                            <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Totals Summary</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">Budget Line</th>
                                                        <th scope="col" class="text-center">Supplier</th>
                                                        <th scope="col" class="text-center">Item Purchased</th>
                                                        <th scope="col" class="text-center">Account</th>
                                                        <th scope="col" class="text-center">Date Purchased</th>
                                                        <th scope="col" class="text-center">Description</th>
                                                        <th scope="col" class="text-center">Unit of Measure</th>
                                                        <th scope="col" class="text-center">Quantity</th>
                                                        <th scope="col" class="text-center">Unit Price</th>
                                                        <th scope="col" class="text-center">VAT Amount</th>
                                                        <th scope="col" class="text-center">Gross Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($expense_retirements as $retirement)
                                                <tr>
                                                    <td scope="col" class="text-center">{{$retirement->item}}</td>
                                                    <td scope="col" class="text-center">{{$retirement->supplier_id}}</td>
                                                    <td scope="col" class="text-center">{{$retirement->item_name}}</td>
                                                    <td scope="col" class="text-center">{{$retirement->account}}</td>
                                                    <td scope="col" class="text-center">{{$retirement->purchase_date}}</td>
                                                    <td scope="col" class="text-center">{{$retirement->description}}</td>
                                                    <td scope="col" class="text-center">{{$retirement->unit_measure}}</td>
                                                    <td scope="col" class="text-center">{{$retirement->quantity}}</td>
                                                    <td scope="col" class="text-center">{{number_format($retirement->unit_price,2)}}</td>
                                                    <td scope="col" class="text-center">{{number_format($retirement->vat_amount,2)}}</td>
                                                    <td scope="col" class="text-center">{{number_format($retirement->gross_amount,2)}}</td>

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
                                                        <td></td>
                                                        <td scope="col" class="text-center">Total</td>
                                                        <td scope="col" class="text-center">{{number_format(ExpenseRetirementController::getExpenseRetirementTotal($retirement->ret_no),2)}}</td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif
                                            @if($expense_retirements->isEmpty())
                                                <table class="table table-sm table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Totals Summary</th>
                                                    </tr>
                                                    <tr>
                                                         <th scope="col" class="text-center">Supplier</th>
                                                        <th scope="col" class="text-center">Item Purchased</th>
                                                        <th scope="col" class="text-center">Account</th>
                                                        <th scope="col" class="text-center">Date Purchased</th>
                                                        <th scope="col" class="text-center">Description</th>
                                                        <th scope="col" class="text-center">Unit of Measure</th>
                                                        <th scope="col" class="text-center">Quantity</th>
                                                        <th scope="col" class="text-center">Unit Price</th>
                                                        <th scope="col" class="text-center">VAT Amount</th>
                                                        <th scope="col" class="text-center">Gross Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($ex_retirement_no_budget as $retirement)
                                                    <tr>
                                                        <td scope="col" class="text-center">{{$retirement->supplier_id}}</td>
                                                        <td scope="col" class="text-center">{{$retirement->item_name}}</td>
                                                        <td scope="col" class="text-center">{{$retirement->account}}</td>
                                                        <td scope="col" class="text-center">{{$retirement->purchase_date}}</td>
                                                        <td scope="col" class="text-center">{{$retirement->description}}</td>
                                                        <td scope="col" class="text-center">{{$retirement->unit_measure}}</td>
                                                        <td scope="col" class="text-center">{{$retirement->quantity}}</td>
                                                        <td scope="col" class="text-center">{{number_format($retirement->unit_price,2)}}</td>
                                                        <td scope="col" class="text-center">{{number_format($retirement->vat_amount)}}</td>
                                                        <td scope="col" class="text-center">{{number_format($retirement->gross_amount,2)}}/=</td>

                                                    </tr>
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
                                                        <td scope="col" class="text-center">{{number_format(ExpenseRetirementController::getExpenseRetirementTotal($retirement->ret_no),2)}}</td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif
                                        </div>
                                </div>

                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-6 mt-2">
                                        <small class="text-primary">Add comments</small>
                                        <form method="POST" action="{{route('expense-retirements.comments')}}">
                                            @csrf
                                            <input type="hidden" name="ret_no" value="{{$ret_no}}">
                                            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <textarea style="resize: none;" rows="2" class="form-control" name="body" placeholder="Add Comments" data-toogle="tooltip" data-placement="top" title="Add Some Comments">

                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Comment</button>
                                                <td scope="col" class="text-center">
                                                  @if($expense_summary->status != 'Confirmed' && Auth::user()->id != $expense_summary->user_id)
                                                        <a href="{{route('approve-expense-retirement',$ret_no)}}" class="btn btn-sm btn-outline-info">Approve</a>
                                                        <a href="{{route('reject-expense-retirement',$ret_no)}}" class="btn btn-sm btn-outline-warning">Reject</a>
                                                  @endif
                                                </td>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-12 ml-1">
                                    <div class="col-lg-12 mt-2">
                                        <h5 class="text-danger">Comments</h5>
                                        <ul class="list-group list-group-flush">
                                            @if(!$comments->isEmpty())
                                               @foreach($comments as $comment)
                                                <li class="list-group-item mb-1 mr-4">{{$comment->body}}
                                                    <span class="float-right badge badge-sm badge-primary">
                                                        {{$comment->username}}
                                                    </span>
                                                </li>
                                               @endforeach
                                            @else
                                                <p>No Comments</p>
                                            @endif
                                        </ul>
                                    </div>
                                </div>


                </div>
            </div>
             @if($flash = session('message'))
                <div id="flash" class="alert alert-info">
                    {{ $flash }}
                </div>
            @endif
        </div>
    </div>
</div>


@endsection

<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script type="text/javascript">
    $(function() {
       $('#flash').delay(500).fadeIn('normal', function() {
          $(this).delay(2500).fadeOut();
       });
    });

    $(document).on('click', '.enable-edit-expense-retirement-line', function(e) {
        e.preventDefault();
        var ex_ret_no = $(this).attr("ret-number");
        var url = '/send-expense-retirement-line/' + ex_ret_no;
        $.get(url, function(data) {
            console.log(data.result);
            window.location = '/edit-expense-retirement-line/' + ex_ret_no;
        });
    });


    $(document).ready(function() {
        $('.preload').fadeOut('3000', function() {
            $('.mydata').fadeIn('2000');
        });
    });
</script>
