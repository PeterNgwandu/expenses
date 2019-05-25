
<?php foreach ($data as $value): ?>

	<tr>
		<td scope="col" class="text-center"><?php if($value->budget_id != 0) echo $value->budget; else echo 'No Budget'; ?></td>
		<td scope="col" class="text-center"><?php if($value->item_id != null) echo $value->item; else echo 'No Budget Line' ?></td>
		<td scope="col" class="text-center">{{$value->supplier_id}}</td>
		<td scope="col" class="text-center">{{$value->ref_no}}</td>
		<td scope="col" class="text-center">{{$value->purchase_date}}</td>
		<td scope="col" class="text-center">{{$value->item_name}}</td>
		<td scope="col" class="text-center">{{$value->unit_measure}}</td>
		<td scope="col" class="text-center">{{$value->quantity}}</td>
		<td scope="col" class="text-center">{{$value->unit_price}}</td>
		<td scope="col" class="text-center">{{$value->vat}}</td>
		<td scope="col" class="text-center">{{$value->account}}</td>
		<td scope="col" class="text-center">{{$value->description}}</td>
		<td scope="col" class="text-center">
			<span>
					<i style="cursor: pointer;" retirement-no="{{$value->ret_no}}" data-id="{{$value->id}}" class="material-icons delete-expense-retirement-line md-10 align-middle mb-1 text-danger">delete_forever</i>
			 </span>
		</td>
	</tr>

<?php endforeach ?>
