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
    <!-- ============================================================== -->
    <link href="{{ URL::asset('public/admin/plugins/datatables/buttons.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/fixedHeader.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/scroller.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/dataTables.colVis.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
  
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
             <div class="container">
                 <!-- end row -->
                 <br>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="card-box">
                                    <h4 class="header-title m-t-0 m-b-30">User Details</h4>
      
                                    <div class="pull-right"><a href="{{ URL::to('admin/unpaid-users') }}" title="View All Users" class="btn btn-danger">View All Users</a></div>
                                    
                                    
                                    <ul class="nav nav-tabs tabs-bordered">
                                       {{-- <li class="active">
                                            <a href="#home-b1" data-toggle="tab" aria-expanded="false">
                                                <span class="visible-xs"><i class="fa fa-home"></i></span>
                                                <span class="hidden-xs">Wallet Details</span>
                                            </a>
                                        </li>--}}
                                        <li class="active">
                                            <a href="#profile-b1" data-toggle="tab" aria-expanded="true">
                                                <span class="visible-xs"><i class="fa fa-user"></i></span>
                                                <span class="hidden-xs">Personal Details</span>
                                            </a>
                                        </li>
                                      
                                        {{--<li class="">
                                            <a href="#settings-b1" data-toggle="tab" aria-expanded="false">
                                                <span class="visible-xs"><i class="fa fa-cog"></i></span>
                                                <span class="hidden-xs">Login History</span>
                                            </a>
                                        </li>--}}
                                        
                                         
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="home-b1">
                                          
                                         </div>
                                        <div class="tab-pane" id="profile-b1">
                                        
                                
                                            <div class="row">
                                                <div class="col-md-2"><b>Username</b></div> <div class='col-md-8'>{{$user->username}}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Email</b></div> <div class='col-md-8'>{{$user->email }}</div>
                                            </div>
                                            
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Account Address</b></div> <div class='col-md-8'>{{$user->account_address}}</div>
                                            </div>
                                             <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Mobile</b></div> <div class='col-md-8'>{{$user->mobile_number }}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Pincode</b></div> <div class='col-md-8'>{{$user->pincode}}</div>
                                            </div>
                                            <br><br>
                                             <div class="row">
                                                <div class="col-md-2"><b>City</b></div> <div class='col-md-8'>{{$user->city_id}}</div>
                                            </div>
                                            <br><br>
                                             <div class="row">
                                                <div class="col-md-2"><b>State</b></div> <div class='col-md-8'>{{$user->state_id}}</div>
                                            </div>
                                            
                                            <br><br>
                                             <div class="row">
                                                <div class="col-md-2"><b>Country</b></div> <div class='col-md-8'>{{$user->country_id}}</div>
                                            </div>
                                            
                                        </div>
                                        <div class="tab-pane active" id="messages-b1">
                                              <div class="row ">
                                                <div class="col-md-2"><b>User Name</b></div> <div class='col-md-8'>{{$user->username}}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Email </b></div> <div class='col-md-8'>
                                                    {{$user->email}}</div>
                                            </div>
                                            <br><br>
                                            

                                            
                                        </div>
                                        
                                        <div class="tab-pane" id="bank-b1">
                                             
                                             <div class="row">
                                                <div class="col-md-2"><b>Account Name </b></div> <div class='col-md-8'>{{$user->account_name}}</div>
                                            </div>
                                             <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Account No </b></div> <div class='col-md-8'>{{$user->account_no}}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>IFSC </b></div> <div class='col-md-8'>{{$user->ifsc}}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Account Address</b></div> <div class='col-md-8'>{{$user->account_address}}</div>
                                            </div>
                                            
                                            
                                         </div>
                                         
                                        <div class="tab-pane" id="settings-b1">
                                             <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">

                                    <h4 class="m-t-0 header-title"><b>Login History</b></h4>
                                    <table id="datatable-responsive"
                                           class="table table-striped  table-colored table-info dt-responsive nowrap" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Email address</th>
                                            <th>Ip Address</th>
                                            <th>Browser</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                     
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                                         </div>
                                         
                                         
                                         
                                         
                                         
                                         
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            
             </div> <!-- container -->
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
    
    @stop
