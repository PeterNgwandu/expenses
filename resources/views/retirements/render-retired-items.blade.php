@if (!$data_no_budget->isEmpty())
<?php foreach ($data_no_budget as $value): ?>

	<tr>
		@if ($data_no_budget->isEmpty())
		<td scope="col" class="text-center">{{$value->budget}}</td>
		<td scope="col" class="text-center">{{$value->item}}</td>
		@else	
			<td scope="col" class="text-center">No Budget</td>
			<td scope="col" class="text-center">No Budget Line</td>
		@endif
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
		<td id="delete-this-row" scope="col" class="text-center">
			
				<span class="delete-retirement-line" id="{{$value->id}}">
					 <i style="cursor: pointer;" class="material-icons md-10 align-middle mb-1 text-danger">delete_forever</i>
				</span>
			
		</td>
	</tr>
	
<?php endforeach ?>	
@elseif($data_no_budget->isEmpty())
<?php foreach ($data as $value): ?>

	<tr>
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
		<td id="delete-this-row" scope="col" class="text-center">
			
				<span class="delete-retirement-line" id="{{$value->id}}">
					 <i style="cursor: pointer;" class="material-icons md-10 align-middle mb-1 text-danger">delete_forever</i>
				</span>
			
		</td>
	</tr>
	
<?php endforeach ?>
@endif
