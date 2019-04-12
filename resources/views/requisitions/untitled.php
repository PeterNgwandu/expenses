<table class="table table-sm table-striped table-dark mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Budget Line</th>
                                            <th scope="col" class="text-center">Item Name</th>
                                            <th scope="col" class="text-center">Unit Measure</th>
                                            <th scope="col" class="text-center">Unit Price</th>
                                            <th scope="col" class="text-center">Quantity</th>
                                            <th scope="col" class="text-center">Description</th>
                                            <th scope="col" class="text-center">Requisition Date</th>
                                            <th scope="col" class="text-center">Total Per Requisition</th>
                                            <th scope="col" class="text-center">Approval Action</th>
                                            <th scope="col" class="text-center">Status</th>

                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submitted_requisitions as $requisition)
                                            <tr>
                                                <td scope="col" class="text-center">{{$requisition->budget}}</td>
                                                <td scope="col" class="text-center">{{$requisition->item}}</td>
                                                <td scope="col" class="text-center">{{$requisition->unit_measure}}</td>
                                                <td scope="col" class="text-center">{{$requisition->unit_price}}</td>
                                                <td scope="col" class="text-center">{{$requisition->quantity}}</td>
                                                <td scope="col" class="text-center">{{$requisition->description}}</td>
                                                <td scope="col" class="text-center">{{$requisition->created_at->toFormattedDateString()}}
                                                </td>
                                                <td scope="col" class="text-center">
                                                    {{$requisition->unit_price * $requisition->quantity}}
                                                </td>
                                                <td scope="col" class="text-center" style="width: 150px;">
                                                    @if($requisition->user_id != Auth::user()->id)
                                                    <a href="{{url('approve-requisition/'.$requisition->id)}}" class="btn btn-sm btn-outline-info">Approve</a>
                                                    @if($requisition->gross_amount > 10000)
                                                    <a href="{{url('reject-requisition/'.$requisition->id)}}" class="btn btn-sm btn-outline-warning">Reject</a>
                                                    @else
                                                    <!-- <p>No Action</p> -->
                                                    @endif
                                                    @endif
                                                    @if($requisition->user_id == Auth::user()->id)
                                                        <span class="badge badge-sm badge-danger">No Action</span>
                                                    @endif
                                                </td>
                                                <td scope="col" class="text-center" style="width: 190px;">
                                                    @if($requisition->status != 'Confirmed')
                                                    <button class="btn btn-sm btn-outline-success"> {{$requisition->status}}
                                                    </button>
                                                    @else
                                                        <button class="btn btn-sm btn-success"> {{$requisition->status}}
                                                    </button>
                                                    @endif
                                                    <a href="{{url('requisition-summary/'.$requisition->id)}}" class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td scope="col" class="text-center font-weight-bold text-success">TOTAL</td>
                                                <td scope="col" class="text-center font-weight-bold text-success">
                                                    <a class="btn btn-sm btn-outline-success" href="{{route('submitted-requisitions')}}">
                                                       Back 
                                                    </a>
                                                </td>
                                                
                                            </tr>
                                    </tbody>
                            </table>