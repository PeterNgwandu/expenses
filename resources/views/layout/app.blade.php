<!DOCTYPE html>
<html lang="en" dir="ltr" class="theme-default">
  <head>
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ env('APP_NAME') }}</title>
    @include('layout.partials.head')
     <style type="text/css">
     .mydata {
        display: none;
    }
    .preload {
        margin: 0px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        margin-top: 10px;
        background: #ffffff;
    }
    .img {
        background: #ffffff;
    }
</style>
  </head>
  <body>

<div class="preload">
    <img class="img" src="{{url('assets/images/giphy.gif')}}">
</div>
    @include('layout.partials.nav')
    @yield('content')
    @include('layout.partials.scripts')

    <script type="text/javascript">
      $(document).ready(function() {
            $('.preload').fadeOut('3000', function() {
                $('.mydata').fadeIn('2000');
            });
        });
    </script>
    
  </body>
</html>
