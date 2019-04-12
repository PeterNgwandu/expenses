
<?php 

use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;
use App\Http\Controllers\Requisitions\RequisitionsController;


 foreach ($data as $data): 

    $item2 = ItemController::get_item_by_id($data->item_id);
    $budget2 = BudgetsController::get_budget_by_id($data->budget_id);

    $latest_row = RequisitionsController::get_latest_row_from_temporary();

     ?>
    
<div class="row requisition">
    <div class="mr-2">
        <select id="budget" name="budget_id" class="form-control budget{{$data->id}}">
            <option value="Select Budget Line">{{$budget2->title}}</option>
            @foreach($budgets as $budget)
                <option value="{{$budget->id}}">{{$budget->title}}</option>
            @endforeach
        </select>
    </div>
    <div class="mr-2">
        <select id="item2" name="item_id2" class="form-control requisition-item">
            <option value="Select Item">{{$item2->item_name}}</option>
            @foreach($items as $item){{$item->id}}
                <option value="{{$item->id}}">{{$item->item_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="mr-2" style="width: 125px">
        
        <input type="text" class="form-control unit_measure" data-id="{{$data->id}}" id="unit_measure{{ $data->id }}" name="unit_measure" placeholder="Unit Measure" value="{{$data->unit_measure}}">
        
    </div>
    <div class="mr-2" style="width: 200px !important;">
        <input type="text" name="description"  placeholder="Description" data-id="{{$data->id}}"  id="description{{ $data->id }}" class="form-control description" value="{{$data->description}}">
    </div>
    <div class="mr-2" style="width: 100px">
        <input type="number" style="" class="form-control quantity" data-id="{{$data->id}}" id="quantity{{ $data->id }}" name="quantity" placeholder="Quantity" value="{{$data->quantity}}" style="">
    </div>
    <div class="mr-2">
        <input type="number" style="" class="form-control unit_price" data-id="{{$data->id}}" id="unit_price{{ $data->id }}" name="unit_price" placeholder="Unit Price" value="{{$data->unit_price}}">
    </div>
    <hr>
    <div class="mr-2">
        <select data-id="{{$data->id}}" name="vat" class="form-control vat">
            <option value="Select VAT Options" selected disabled>VAT Options</option>
            <option value="vat_inclusive">VAT Inclusive</option>
            <option value="vat_exclusive">VAT Exclusive</option>
            <option value="non_vat">Non VAT</option>
        </select>
    </div>
    <div class="mr-2">
        <select name="accounts" class="form-control accounts">
            <option value="Select Account" selected disabled="">Select Account</option>
            @foreach($account as $account)
                <option value="{{$account->id}}">{{$account->account_name}}</option>
            @endforeach
        </select>
    </div>

   <!--  <div class="mr-0">
        <div class="input-group mb-2" >
        <span class="input-group-prepend">
          <span class="input-group-text" style="background: #AB2F15;color: #ffffff">=</span>
        </span>
        <input type="text" class="form-control disabled total" name="total" data-id="{{$data->id}}" id="total{{$data->id}}"  value="{{ number_format(RequisitionsController::getItemBudgetTotal($data->id),2)}}" readonly="">
    </div>
    </div> -->
    <div style="width: 60px; padding-top: 4px;">
        <b class="" style="display: inline-block;"><i id="{{ $data->id }}" class="material-icons clear-btn align-middle mb-1" style="cursor: pointer; color: red">clear</i></b>
        <?php if ($data->id == $latest_row->id): ?>
            <b class="new-row" style="display: inline-block;"><i class="material-icons align-middle mb-1" style="cursor: pointer; color: purple">add</i></b>
        <?php endif ?>
    </div>
                                            
</div>
<?php endforeach ?>

<div class="row requisition render-requisition-row">
                                         
</div>