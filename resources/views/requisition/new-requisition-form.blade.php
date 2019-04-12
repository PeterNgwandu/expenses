<!-- <div class="col-lg-16 render-requisition-form"> -->
    <!-- <div class="row">
        <div class="col-6">
             <select id="budget" name="budget_id" class="form-control">
                 <option value="Select Budget" selected disabled>
                     Select Budget
                 </option>
                 @foreach($budgets as $budget)
                    <option value="{{$budget->id}}">
                        {{$budget->title}}
                    </option>
                 @endforeach
             </select>
        </div>
    </div> -->
   <!--  <input type="hidden" name="req_no">
    <div class="row requisition mt-3">
        <div class="col-2">
             <select id="item" name="item_id" class="form-control item requisition-item">
                 <option value="Select Budget Line" selected disabled>
                    Budget Line
                 </option> -->
                 <!-- @foreach($items as $item)
                    <option value="{{$item->id}}">
                        {{$item->item_name}}
                    </option>
                 @endforeach -->
<!--              </select>
        </div>
        <div class="col-4">
            <input type="text" value="" class="form-control" placeholder="Budget Line Description">
        </div>
        <div class="col">
            <input type="text" name="item_name" class="form-control" placeholder="Item Name">
        </div>
        <div class="col-4">
            <input type="text" value="" name="description" class="form-control" placeholder="Item Description">
        </div>
    </div>  

    <div class="row mt-3">
        <div class="col">
            <input type="text" name="unit_measure" class="form-control" placeholder="Unit of Measure">
        </div>
        <div class="col">
            <input type="number" name="quantity" class="form-control" placeholder="Quantity">
        </div>
        <div class="col">
            <input type="number" name="unit_price" class="form-control" placeholder="Unit Price">
        </div>
        <div class="col">
            <select name="vat" class="form-control">
                <option value="VAT Options">VAT Options</option>
                <option value="VAT Exclusive">VAT Exclusive</option>
                <option value="VAT Inclusive">VAT Inclusive</option>
                <option value="Non VAT">Non VAT</option>
            </select>
        </div>
        <div class="col">
            <select name="account_id" class="form-control accounts">
                <option value="VAT Options">Select Account</option>
                @foreach($accounts as $account)
                    <option value="{{$account->id}}">
                        {{$account->account_name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div> 

    <button type="button" class="btn btn-sm btn-outline-info mt-3 new-row">
        <span><i class="material-icons md-30 align-middle text-primary">add</i>Add Budget Line </span>
    </button>

<div class="render-requisition-row">
                                         
</div> -->

<!-- </div>          -->
<!-- <script type="text/javascript">
    
    $(document).on('click', '.new-row', function(){
        var url = '/add-new-form';
        $.get(url, function(data){
            $('.render-requisition-row').html(data.result);
        });
    });
    
</script> -->
