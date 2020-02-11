<head>
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="shortcut icon" href="{{ URL::asset('public/images/fevi.png') }}" type="image/x-icon"/>
	@if(Request::segment(1)=="product")
		@include('social::meta-article', [
               'title'         => $product_details->name,
               'description'   => substr($product_details->description,0,10),
               'image'         => URL::asset('public/admin/uploads/product/'.$product_details->product_image[0]->image),
               'author'        => 'Grocito Style'
           ])
	@endif
	@if(Request::segment(1)=="refer-earn")
		@include('social::meta-article', [

               'title'         => "Refer Code",
               'description'   => "asdsa",
               'image'   => "",
               'author'        => 'Grocito Style'
           ])
	@endif
	<title>Grocito</title>
	<link rel="stylesheet" href="{{ URL::asset('public/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('public/css/materialize.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('public/css/responsive.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ URL::asset('public/css/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('public/css/owl.theme.default.min.css') }}">
	<link rel="stylesheet" href="{{URL::asset('public/css/jquery-ui.css')}}">
	<link href="{{URL::asset('public/css/ubislider.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{URL::asset('public/css/custom.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('public/css/datepicker.css') }}">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">


</head>