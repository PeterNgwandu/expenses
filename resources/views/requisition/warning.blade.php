@extends('layout.app')

@section('content')

<div class="mdk-drawer-layout js-mdk-drawer-layout" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">

        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-earnings">
                        <div class="card-header bg-faded">
                            <div class="row align-items-center">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alet-danger">
                                            <h4 class="text-danger">Ooops! You cannot approve your own requisition</h4>
                                            <a href="{{route('submitted-requisitions')}}" class="btn btn-sm btn-outline-primary">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                 
                </div>
            </div>
        </div>

    </div>
</div>


@endsection
