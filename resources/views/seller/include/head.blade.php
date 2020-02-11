<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	 <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{env('APP_NAME')}}</title>
    <link rel="stylesheet" href="{{ URL::asset('public/front/css/custom.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/front/css/style.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script>
		ASSET_URL = '{{ URL::asset('') }}';
		BASE_URL='{{ URL::to('/') }}';
	</script>
	 <?php
	   if(Auth::user()->banned==1)
	   {
		  ?>
		  <script>
		  window.location=BASE_URL+"/seller/logout";
		  </script>
		  <?php
	   }
	 ?>
</head>