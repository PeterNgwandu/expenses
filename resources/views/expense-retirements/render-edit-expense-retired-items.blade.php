
<?php foreach ($data as $value): ?>

<tr>
		<td scope="col" class="text-center"><input disabled type="text" class="form-control" name="budget_id" value="<?php if($value->budget_id != 0) echo $value->budget; else echo 'No Budget'; ?>"></td>
		<td scope="col" class="text-center"><input disabled type="text" class="form-control" name="item_id" value="<?php if($value->item_id != null) echo $value->item; else echo 'No Budget Line' ?>"></td>
		<td scope="col" class="text-center"><input id="supplier_id" type="text" class="form-control" name="supplier_id" value="{{$value->supplier_id}}"></td>
		<td scope="col" class="text-center"><input id="ref_no" type="text" class="form-control" name="ref_no" value="{{$value->ref_no}}"></td>
		<td scope="col" class="text-center"><input id="purchase_date" type="text" class="form-control datepicker" name="purchase_date" value="{{$value->purchase_date}}"></td>
		<td scope="col" class="text-center"><input id="item_namme" type="text" class="form-control" name="item_name" value="{{$value->item_name}}"></td>
		<td scope="col" class="text-center"><input id="unit_measure" type="text" class="form-control" name="unit_measure" value="{{$value->unit_measure}}"></td>
		<td scope="col" class="text-center"><input id="quantity" type="text" class="form-control" name="quantity" value="{{$value->quantity}}"></td>
		<td scope="col" class="text-center"><input id="unit_price" type="text" class="form-control" name="unit_price" value="{{$value->unit_price}}"></td>
		<td scope="col" class="text-center"><input id="vat" type="text" class="form-control" name="vat" value="{{$value->vat}}"></td>
		<td scope="col" class="text-center">
			<select id="account" class="form-control" name="account_id" id="account">
					<option value="{{$value->account}}">{{$value->account}}</option>
					@foreach($accounts as $account)
							<option value="{{$value->account}}">{{$value->account}}</option>
					@endforeach
			</select>
		</td>
		<td scope="col" class="text-center"><input id="description" type="text" class="form-control" name="description" value="{{$value->description}}"></td>
		<td scope="col" class="text-center">
			<span>
					<i style="cursor: pointer;" retirement-no="{{$value->ret_no}}" data-id="{{$value->id}}" class="material-icons delete-expense-retirement-line md-10 align-middle mb-1 text-danger">delete_forever</i>
			 </span>
		</td>
</tr>

<?php endforeach ?>
