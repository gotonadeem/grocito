<?php
/**
 * Created by PhpStorm.
 * User: wingstud
 * Date: 10/8/17
 * Time: 12:49 PM
 */
?>
@extends('admin.layout.admin')
@section('content')
    <!-- DataTables -->
    <link href="
    " rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/buttons.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/fixedHeader.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/scroller.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/dataTables.colVis.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- end row -->
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                        <div>
                            <input type="text" id="term" />
                            <input type="text" id="term2" />
                            <a onclick="checkfunction()" class="btn btn-success">check</a>
                        </div>
                            
                            <script>
                               function checkfunction()
                               {
                                   var term = $('#term').val();
                                   var term2 = $('#term2').val();
                                if(term==term2)
                                {
                                    alert('match');
                                }
                                else
                                {
                                    alert('missmatch');
                                }
                              }
                            </script>
                           <!-- <div class="pull-right"><a href="{{ URL::to('admin/user/export-excel') }}" title="Export In Excel"  class="btn btn-danger">Export Excel</a> </div>-->

                            <h4 class="m-t-0 header-title"><b>List of Paid Unverified Users</b></h4>
                            <table id="datatable-fixed-header" class="table table-striped table-bordered table-colored table-info">
                                <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Uname</th>
                                    <th>Name</th>
                                    <th>Email</th>

                                    <th>My Sponsor Id</th>

                                    <th>Refered By</th>
                                     <th>Transaction Id</th>
                                    <th>Payment Type</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
    </div>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script src="{{ URL::asset('public/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/plugins/datatables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ URL::asset('public/admin/plugins/datatables/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/plugins/datatables/dataTables.colVis.js') }}"></script>
    <script src="{{ URL::asset('public/admin/plugins/dataTables.responsive.min.js') }}"></script>
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/paid_unverified_user.js') }}"></script>

@stop
