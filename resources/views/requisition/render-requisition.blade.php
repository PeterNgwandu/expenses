<?php foreach ($data as $value): ?>

	<tr>
		<!-- <td><input type='checkbox' name='record[]' value="{{$value->id}}"></td> -->
		<td scope="col" class="text-center"><?php if($value->budget_id != 0) echo $value->budget; else echo 'No Budget'; ?></td>
		<td scope="col" class="text-center"><?php if($value->item_id != null) echo $value->item; else echo 'No Budget Line'; ?></td>
		<td scope="col" class="text-center">{{$value->req_no}}</td>
		<td scope="col" class="text-center">{{$value->item_name}}</td>
		<td scope="col" class="text-center">{{$value->unit_measure}}</td>
		<td scope="col" class="text-center">{{$value->quantity}}</td>
		<td scope="col" class="text-center">{{$value->unit_price}}</td>
		<td scope="col" class="text-center">{{$value->vat}}</td>
		<td scope="col" class="text-center">{{$value->account}}</td>
		<td scope="col" class="text-center">{{$value->description}}</td>
	</tr>

<?php endforeach ?>
