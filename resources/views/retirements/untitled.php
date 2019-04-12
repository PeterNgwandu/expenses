<input id="req" type="hidden" name="req_id[]" value="<?php if(!empty($requisition->user_id)) echo($requisition->id); else echo 'Null' ?>">
                    <input id="budget" type="hidden" name="budget_id[]" value="<?php if(!empty($requisition->user_id)) echo($requisition->budget_id); else echo 'Null' ?>">
                    <input id="item" type="hidden" name="item_id[]" value="<?php if(!empty($requisition->user_id)) echo($requisition->item_id); else echo 'Null' ?>">
                    <input type="hidden" name="ret_no[]"> 
                    <input type="hidden" name="user_id" id="user_id" value="{{$requisition->user_id}}">
                    <table class="table table-sm mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-center">Select</th>
                                <th scope="col" class="text-center">Supplier</th>
                                <th scope="col" class="text-center">Reference No.</th>
                                <th scope="col" class="text-center">Purchase Date</th>
                                <th scope="col" class="text-center">Item Name</th>
                                <th scope="col" class="text-center">Unit Measure</th>
                                <th scope="col" class="text-center">Unit Price</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-center">VAT</th>
                                <th scope="col" class="text-center">Account</th>
                                <th scope="col" class="text-center">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                                
                        </tbody>
                </table>
