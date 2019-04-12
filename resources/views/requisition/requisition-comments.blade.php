<?php 

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Budgets\BudgetsController;

// foreach ($data as $data) :

?>

@extends('layout.app')

@section('content')

<style type="text/css">
    .requisition div {
        padding: 0px; margin-left: 0px; width: 150px;
    }
    .requisition div input {
        margin: 0px; padding: 0px; width: 100%
    }
    .requisition i:hover {
        color: #fff !important; background: purple
     }

    #flash {
        position: absolute;
        bottom: 10px;
        right: 20px;
        z-index: 10;
    } 
</style>
<div class="mdk-drawer-layout js-mdk-drawer-layout" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <h2 class="card-title">Add Comments</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('comment.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="req_no" value="{{$requisition_no}}">
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <textarea style="resize: none;" rows="7" class="form-control" name="body" placeholder="Add Comments">
                                                
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-outline-primary">Comment</button>
                            </form>
                            @if($flash = session('message'))
                                <div id="flash" class="alert alert-info">
                                    {{ $flash }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
