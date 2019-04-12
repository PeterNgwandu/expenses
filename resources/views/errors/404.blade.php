@extends('layout.app')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout" data-fullbleed data-push data-has-scrolling-region>
    <div class="mdk-drawer-layout__content mdk-header-layout__content--scrollable">
        <!-- CONTENT BODY -->

        <div class="container h-100 d-flex justify-content-center align-items-center flex-column">

            <div class="d-flex align-items-center justify-content-center mb-3 text-danger">
                <i class="material-icons md-128 mr-2">error</i>
                <h3 class="mb-0">Error 404</h3>
            </div>
            <div class="d-flex align-items-center">
                <p class="h5">
                    Sorry. The page you're looking for does not exist!
                </p>
            </div>
            <p class="mb-0">
                Click <a href="dashboard.html">here</a> to go back to your dashboard.
            </p>
        </div>

    </div>
</div>
@endsection