@include('admin.includes.admin_head')
<body>
<div class="loader_div">
<img class="loader" src="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}">
</div>
<div id="wrapper">
@include('admin.includes.admin_left_sidebar')
 <div id="page-wrapper" class="gray-bg">
		 <div class="alert alert-success message" style="display:none" id="success_alert"><li class="fa fa-check"></li></div>
	 @if(Session::has('success_message'))
		 <div class="alert alert-success message"><li class="fa fa-check"></li>{{ Session::get('success_message') }}</div>
	 @endif
	 @if(Session::has('error_message'))
		 <div class="alert alert-error message"><li  class="fa fa-window-close"></li>{{ Session::get('error_message') }}</div>
	 @endif
 @include('admin.includes.admin_header')
  @yield('content')
</div>
</div>
{{--@stack('scripts')--}}
</body>

