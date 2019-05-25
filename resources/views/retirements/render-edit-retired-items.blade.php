
<!-- <td scope="col" class="text-center">No Budget</td>
<td scope="col" class="text-center">No Budget Line</td> -->
@foreach($data_no_budget as $data_no_budget)
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->activity_name}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->supplier_id}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->ref_no}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->purchase_date}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->item_name}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->unit_measure}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->quantity}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->unit_price}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->vat}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->account}}"></td>
<td scope="col" class="text-center"><input type="text" class="form-control" value="{{$data_no_budget->description}}"></td>
<td id="delete-this-row" scope="col" class="text-center">

		<span class="delete-retirement-line" id="{{$data_no_budget->id}}">
			 <i style="cursor: pointer;" class="material-icons md-10 align-middle mb-1 text-danger">delete_forever</i>
		</span>

</td>
@endforeach
