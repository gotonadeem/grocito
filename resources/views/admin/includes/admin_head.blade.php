<!DOCTYPE html>
  <html>
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
      <meta name="author" content="Coderthemes">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <!-- App favicon -->
      <!--<link rel="shortcut icon" href="{{ URL::asset('public/admin/img/logo.png') }}">-->
      <!-- App title -->
      <title> {{env('APP_NAME')}} | Admin Dashboard</title>
      <link href="{{ URL::asset('public/admin/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ URL::asset('public/admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
      <link href="{{ URL::asset('public/admin/css/animate.css') }}" rel="stylesheet">
      <link href="{{ URL::asset('public/admin/css/style.css') }}" rel="stylesheet">
      <!--Morris Chart CSS -->
      <link rel="stylesheet" href="{{ URL::asset('public/admin/css/custom.css') }}">
      <script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>

  </head>