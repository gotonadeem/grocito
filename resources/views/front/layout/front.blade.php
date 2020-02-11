<!doctype html>
<html>
@include('front.includes.head')
<body>
<!--<div class="loader_div">
<img class="loader" src="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}">
</div>-->
  @include('front.includes.header')
  @yield('content')
  @include('front.includes.footer')
  @yield('scripts')
</body>
</html>
