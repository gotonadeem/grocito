<!doctype html>
<html>
@include('seller.include.head')
<body>
<div class="loader_div">
<img class="loader" src="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}">
</div>
<div class="wraper">
@include('seller.include.header')
@include('seller.include.navigation')

@yield('content')
</div>
@include('seller.include.footer')
</body>
</html>