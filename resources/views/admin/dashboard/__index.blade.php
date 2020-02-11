@extends('admin.layout.admin')
@section('content')
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
           <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
               
                <div class="row text-center">

                       <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <div class="wigdet-one-content">
                                <p class="m-0 text-uppercase font-600 font-secondary text-overflow">Total Users</p>
                                <h2 class="text-primary"><span data-plugin="counterup">{{$total_users}}</span> </h2>
                            </div>
                        </div>
                    </div><!-- end col -->

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <div class="wigdet-one-content">
                                <p class="m-0 text-uppercase font-600 font-secondary text-overflow">Users Today</p>
                                <h2 class="text-dark"><span data-plugin="counterup">{{$user_today}}</span> </h2>
                            </div>
                        </div>
                    </div><!-- end col -->

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <div class="wigdet-one-content">
                                <p class="m-0 text-uppercase font-600 font-secondary text-overflow">Users This Month</p>
                                <h2 class="text-success"><span data-plugin="counterup">{{$user_this_month}}</span></h2>
                            </div>
                        </div>
                    </div><!-- end col -->


                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <div class="wigdet-one-content">
                                <p class="m-0 text-uppercase font-600 font-secondary text-overflow">verified Users</p>
                                <h2 class="text-primary"><span data-plugin="counterup">{{$user_varified}}</span> </h2>
                            </div>
                        </div>
                    </div><!-- end col -->

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <div class="wigdet-one-content">
                                <p class="m-0 text-uppercase font-600 font-secondary text-overflow">unverified Users</p>
                                <h2 class="text-danger"><span data-plugin="counterup">{{$user_unvarified}}</span> </h2>
                            </div>
                        </div>
                    </div><!-- end col -->
                    


                </div>
                <!-- end row -->

                    <!-- end col -->

                </div>
                <!-- end row -->

            </div> <!-- container -->
        </div> <!-- content -->
    </div>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
@stop
