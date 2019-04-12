
                                        
                                        <div class="mr-2">
                                            <select id="budget" name="budget_id" class="form-control budget">
                                                <option value="Select Budget Line" selected disabled>Select Budget</option>
                                                @foreach($budgets as $budget)
                                                    <option value="{{$budget->id}}">{{$budget->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mr-2">
                                            <select id="item" name="item_id" class="form-control requisition-item">
                                                <option value="Select Item">Select Item</option>
                                                <!-- @foreach($items as $item)
                                                    <option value="{{$item->id}}">{{$item->item_name}}</option>
                                                @endforeach -->
                                            </select>
                                        </div>
                                        <div class="mr-2" style="width: 125px">
                                            <input type="text" class="form-control" name="unit_measure" placeholder="Unit Measure" value="">
                                        </div>
                                        <div class="mr-2">
                                            <input type="text" name="description"  placeholder="Description" class="form-control">
                                        </div>
                                        <div class="" style="width: 100px">
                                            <input type="number" style="" class="form-control" name="quantity" placeholder="Quantity" value="" style="">
                                        </div>
                                        <div class="mr-2">
                                            <input type="number" style="" class="form-control" name="unit_price" placeholder="Unit Price" value="">
                                        </div>
                                        <hr>
                                        <div class="mr-2">
                                            <select name="accounts" class="form-control vat">
                                                <option value="Select VAT Options" selected disabled>Select VAT Options</option>
                                                <option value="vat_inclusive">VAT Inclusive</option>
                                                <option value="vat_exclusive">VAT Exclusive</option>
                                                <option value="non_vat">Non VAT</option>
                                            </select>
                                        </div>
                                        <div class="mr-2">
                                            <select name="accounts" class="form-control accounts">
                                                <option value="Select Account" selected disabled="">Select Account</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{$account->id}}">{{$account->account_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- <div class="mr-0">
                                            <div class="input-group mb-2" style="width: 160px;>
                                            <span class="input-group-prepend">
                                              <span class="input-group-text" style="background: #AB2F15;color: #ffffff">=</span>
                                            </span>
                                            <input type="text" class="form-control" name="total"  value="">
                                        </div>
                                        </div> -->
                                        <div style="width: 50px">
                                            <b class="new-row"><i class="material-icons md-36 align-middle mb-1" style="cursor: pointer; color: purple">add</i></b>
                                        </div>

