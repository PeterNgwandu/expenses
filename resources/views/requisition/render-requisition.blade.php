<?php 
use App\Http\Controllers\Requisitions\RequisitionsController;

?>
<?php foreach ($data as $value): ?>
	<form action="" method="POST" id="requisition_form"></form>
	<tr>
		<!-- <td><input type='checkbox' name='record[]' value="{{$value->id}}"></td> -->
		<td scope="col" class="text-center"><input data-id="{{$value->id}}" id="temp_budget_id" class="form-control" type="text" name="budget_id" value="<?php if($value->budget_id != 0) echo $value->budget; else echo 'No Budget'; ?>"></td>
		<td scope="col" class="text-center"><select data-id="{{$value->id}}" style="width:110px;" id="temp_item_id" class="form-control" name="item_id">
				<option value="<?php if($value->item_id != null) echo $value->item; else echo 'No Budget Line'; ?>"><?php if($value->item_id != null) echo $value->item; else echo 'No Budget Line'; ?></option>
				@foreach ($budget_line as $item)
					<option value="{{$item->id}}">{{$item->item_name}}</option>
				@endforeach
			</select>
		</td>

		<td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:90px;" id="temp_req_no" class="form-control" type="text" name="req_no" disabled value="{{$value->req_no}}"></td>
		<td scope="col" class="text-center"><input data-id="{{$value->id}}" id="temp_item_name" class="form-control" type="text" name="item_name" value="{{$value->item_name}}"></td>
		<td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:70px;" id="temp_unit_measure" class="form-control" type="text" name="unit_measure" value="{{$value->unit_measure}}"></td>
		<td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:55px;" id="temp_quantity" class="form-control" type="text" name="quantity" value="{{$value->quantity}}"></td>
		<td scope="col" class="text-center"><input data-id="{{$value->id}}" id="temp_unit_price" class="form-control" type="text" name="unit_price" value="{{$value->unit_price}}"></td>
		<td scope="col" class="text-center"><select style="width:130px;" data-id="{{$value->id}}" id="temp_vat" class="form-control" name="vat">
			<option value="{{$value->vat}}"  selected>{{$value->vat}}</option>
			<option value="VAT Exclusive">Exclusive</option>
			<option value="VAT Inclusive">Inclusive</option>
			<option value="Non VAT">Non VAT</option>	
		</select></td>
		<td scope="col" class="text-center"><select data-id="{{$value->id}}" id="temp_account" class="form-control" name="account_id">
			<option value="{{$value->account}}">{{$value->account}}</option>
			@foreach ($accounts as $account)
				<option value="{{$account->id}}">{{$account->account_name}}</option>
			@endforeach
		</select></td>
		<td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:140px;" id="temp_description" class="form-control" type="text" name="description" value="{{$value->description}}"></td>
		<td id="delete-this-row" scope="col" class="text-center">
			<a class="delete-requisition-line" id="{{$value->id}}" href="">
				<span>
					 <i style="cursor: pointer;" class="material-icons md-10 align-middle mb-1 text-danger">delete_forever</i>
				</span>
			</a>
		</td>
	</tr>

<?php endforeach ?>
<div class="" style="margin-right: 28px; margin-top: -10px">
	<button type="submit" req-no="{{RequisitionsController::getTheLatestRequisitionNumber()}}" class="btn permanent-requisition float-right btn-outline-primary mt-3 ml-1">Submit Requisition</button>
 </div>

		{{-- <td scope="col" class="text-center"><input class="form-control" type="text" name="item_id" value=""></td> --}}
		
