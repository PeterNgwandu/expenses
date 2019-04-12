

<?php foreach ($data as $value): ?>

	<tr>
		<td><input type='checkbox' name='record'></td>
		<td scope="col" class="text-center">{{$value->budget}}</td>
		<td scope="col" class="text-center">{{$value->item}}</td>
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
	</tr>
	
<?php endforeach ?>