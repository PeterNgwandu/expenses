<?php 

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Requisitions\RequisitionsController;

?>

@if ($value->budget_id != 0 && Auth::user()->id == $value->user_id)
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" id="temp_budget_id" class="form-control" type="text" name="budget_id" value="<?php if($value->budget_id != 0) echo $value->budget; else echo 'No Budget'; ?>"></td>
    <td scope="col" class="text-center"><select data-id="{{$value->id}}" style="width:110px;" id="perm_item_id" class="form-control" name="item_id">
            <option value="<?php if($value->item_id != null) echo $value->item; else echo 'No Budget Line'; ?>"><?php if($value->item_id != null) echo $value->item; else echo 'No Budget Line'; ?></option>
            @foreach ($budget_line as $item)
                <option value="{{$item->id}}">{{$item->item_name}}</option>
            @endforeach
        </select>
    </td>

    <td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:90px;" id="temp_req_no" class="form-control" type="text" name="req_no" disabled value="{{$value->req_no}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" id="perm_activity_name" class="form-control" type="text" name="activity_name" value="{{$value->activity_name}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" id="perm_item_name" class="form-control" type="text" name="item_name" value="{{$value->item_name}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:70px;" id="perm_unit_measure" class="form-control" type="text" name="unit_measure" value="{{$value->unit_measure}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:55px;" id="perm_quantity" class="form-control" type="text" name="quantity" value="{{$value->quantity}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" id="perm_unit_price" class="form-control" type="text" name="unit_price" value="{{$value->unit_price}}"></td>
    <td scope="col" class="text-center"><select style="width:130px;" data-id="{{$value->id}}" id="perm_vat" class="form-control" name="vat">
        <option value="{{$value->vat}}"  selected>{{$value->vat}}</option>
        <option value="VAT Exclusive">Exclusive</option>
        <option value="VAT Inclusive">Inclusive</option>
        <option value="Non VAT">Non VAT</option>	
    </select></td>
    <td scope="col" class="text-center"><select data-id="{{$value->id}}" id="perm_account" class="form-control" name="account_id">
        <option value="{{$value->account}}">{{$value->account}}</option>
        @foreach ($accounts as $account)
            <option value="{{$account->id}}">{{$account->account_name}}</option>
        @endforeach
    </select></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:140px;" id="perm_description" class="form-control" type="text" name="description" value="{{$value->description}}"></td>
    <td id="delete-this-row" scope="col" class="text-center">
        <a class="delete-requisition-by-id" data-id="{{$value->req_no}}" id="{{$value->id}}" href="">
            <span>
                <i style="cursor: pointer;" class="material-icons deleting-requisition md-10 align-middle mb-1 text-danger">delete_forever</i>
            </span>
        </a>
    </td> 
@elseif($value->budget_id == 0 && Auth::user()->id == $value->user_id)
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" id="no_budget_perm_budget_id" style="width:95px;" class="form-control" type="text" name="budget_id" disabled value="<?php echo 'No Budget'; ?>"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" disabled style="width:125px;" id="no_budget_perm_item_id" class="form-control" name="item_id" value="<?php echo 'No Budget Line'; ?>"></td>

    <td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:90px;" id="temp_req_no" class="form-control" type="text" name="req_no" disabled value="{{$value->req_no}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" id="no_budget_perm_activity_name" class="form-control" type="text" name="activity_name" value="{{$value->activity_name}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" id="no_budget_perm_item_name" class="form-control" type="text" name="item_name" value="{{$value->item_name}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:70px;" id="no_budget_perm_unit_measure" class="form-control" type="text" name="unit_measure" value="{{$value->unit_measure}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:55px;" id="no_budget_perm_quantity" class="form-control" type="text" name="quantity" value="{{$value->quantity}}"></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" id="no_budget_perm_unit_price" class="form-control" type="text" name="unit_price" value="{{$value->unit_price}}"></td>
    <td scope="col" class="text-center"><select style="width:130px;" data-id="{{$value->id}}" id="no_budget_perm_vat" class="form-control" name="vat">
        <option value="{{$value->vat}}"  selected>{{$value->vat}}</option>
        <option value="VAT Exclusive">Exclusive</option>
        <option value="VAT Inclusive">Inclusive</option>
        <option value="Non VAT">Non VAT</option>	
    </select></td>
    <td scope="col" class="text-center"><select data-id="{{$value->id}}" id="no_budget_perm_account" class="form-control" name="account_id">
        <option value="{{$value->account}}">{{$value->account}}</option>
        @foreach ($accounts as $account)
            <option value="{{$account->id}}">{{$account->account_name}}</option>
        @endforeach
    </select></td>
    <td scope="col" class="text-center"><input data-id="{{$value->id}}" style="width:140px;" id="no_budget_perm_description" class="form-control" type="text" name="description" value="{{$value->description}}"></td>
    <td id="delete-this-row" scope="col" class="text-center">
        <a class="delete-requisition-by-id" data-id="{{$value->req_no}}" id="{{$value->id}}" href="">
            <span>
                    <i style="cursor: pointer;" class="material-icons deleting-requisition md-10 align-middle mb-1 text-danger">delete_forever</i>
            </span>
        </a>
    </td>
@endif
		
		
