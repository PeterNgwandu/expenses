<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Accounts\AccountController;

?>
@extends('layout.app')

@section('content')

<div class="mdk-drawer-layout js-mdk-drawer-layout mydata" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h4 class="card-title">Accounts</h4>
                                     <a href="#" class="float-right btn btn-primary" data-toggle="modal" data-target="#add_items">Add Account</a>
                                </div>
                            </div>
                        </div>
                        <table class="table table-sm  mb-0">
                                    <thead>
                                        <tr>
                                            <th style="border:none;" scope="col" class="text-center">#ID.</th>
                                            <th style="border:none;" scope="col" class="text-center">Account Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($accounts_types as $type)
                                        <tr>
                                            <td style="border:none;" class="align-middle text-center">
                                              <span>
                                                 <i class="material-icons delete-row md-10 align-middle mb-1 text-primary">subdirectory_arrow_right</i>
                                              </span>
                                            </td>
                                            <td style="border:none;" class="align-middle text-center font-weight-bold">{{ $type->account_type_name }}</td>
                                        </tr>


                                           @foreach(AccountController::get_sub_account_type_by_account_type_id($type->id) as $sub_account_type)

                                            <tr>
                                                <td style="border:none;"></td>
                                                <td style="border:none;" class="align-middle text-center">
                                                  <span>
                                                     <i class="material-icons delete-row md-10 align-middle mb-1 text-primary">subdirectory_arrow_right</i>
                                                  </span>
                                                </td>
                                                <td style="border:none;" class="align-middle text-center">{{ $sub_account_type->account_subtype_name }}</td>
                                            <!-- </tr>
                                            <tr> -->
                                            <!-- <td></td>
                                            <td></td> -->
	                                    		<th scope="col" class="text-center">#Account No.</th>

	                                    		<th scope="col" class="text-center">Account</th>
                                    		</tr>
                                            <tr>
                                           		  <td></td>
                                           		  <td></td>

                                                <td style="border:none;" class="align-middle text-center">

                                                </td>
                                                @foreach(AccountController::get_accounts_by_account_subtype_id($sub_account_type->id) as $account)
                                                <td class="align-middle text-center text-success font-weight-bold">
                                                  <span>
                                                     <i class="material-icons delete-row md-10 align-middle mb-1 text-primary">subdirectory_arrow_right</i>
                                                  </span>
                                                  {{ $account->account_no }}</td>
                                                <td class="align-middle text-center text-success font-weight-bold">{{ $account->account_name }}</td>
                                                @endforeach
                                            </tr>
                                           @endforeach


                                       @endforeach
                                    </tbody>
                                </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<div class="modal fade" id="add_items" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Add Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                  <form method="POST" action="{{ route('accounts.store') }}">
                        @csrf
                       	<div class="row">
                       		<div class="col-lg-4">
                       			<div class="form-group">
                       				<input type="text" class="form-control" placeholder="Account No" name="account_no">
                       			</div>
                       		</div>
                       		<div class="col-lg-4">
                       			<div class="form-group">
		                   			<input type="text" class="form-control" placeholder="Account Name" name="account_name">
                       			</div>
                       		</div>
                       		<div class="col-lg-4">
                       			<div class="form-group">
	                       			<select name="sub_accounts_types" class="form-control">
                       					<option value="Select Sub Account Type">Select Sub Account Type</option>
                       					@foreach($sub_types as $type)
                       						<option value="{{ $type->id }}">{{ $type->account_subtype_name }}</option>
                       					@endforeach
                       				</select>
                       			</div>

                       		</div>
                       	</div>
                       	<input type="hidden" name="user_id" value="">
                       	<div class="row">
                       		<div class="col-lg-12">
                       			<div class="form-group">
	                      			<textarea class="form-control" col="90" name="description" placeholder="Description"></textarea>
                       			</div>
                       		</div>
                       	</div>
                        <button type="submit" class="btn btn-sm btn-primary">Add Account</button>
                    </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
