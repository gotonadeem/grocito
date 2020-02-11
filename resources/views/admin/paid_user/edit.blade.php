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
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- end row -->
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="col-sm-12 col-xs-12 col-md-12">
                            <div class="pull-right"><a href="{{ URL::to('admin/subadmin/view-all-subadmin') }}" title="View all"  class="btn btn-danger">View All</a></div>
                            <h4 class="header-title m-t-0">Update Sub-admin</h4>
                            <p class="text-muted font-13 m-b-10"></p>
                            <div class="p-70">
                                {{ Form::model($users,array('url' => 'admin/subadmin/edit/'.$users->id,'class'=>'form-horizontal')) }}
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Name<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {{Form::text('name',NULL,['class'=>'form-control','id'=>'name','placeholder'=>'Enter name'])}}
                                        <div class="error-message">{{ $errors->first('name') }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Email<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {{Form::text('email',$users->email,['class'=>'form-control','id'=>'email','placeholder'=>'Enter email'])}}
                                        <div class="error-message">{{ $errors->first('email') }}</div>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="hori-pass1" class="col-sm-4 form-control-label">Password<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {{Form::password('password',['class'=>'form-control','id'=>'password','placeholder'=>'Enter Password'])}}
                                        <div class="error-message">{{ $errors->first('password') }}</div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="hori-pass2" class="col-sm-4 form-control-label">Confirm Password
                                        <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {{Form::password('password_confirmation',['class'=>'form-control','id'=>'password_confirmation','placeholder'=>'Enter Confirm password'])}}
                                        <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <button type="submit" name="add_subadmin" value="add_subadmin"  class="btn btn-primary waves-effect waves-light">
                                            Register
                                        </button>
                                        <button type="reset"
                                                class="btn btn-default waves-effect m-l-5">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>

                        </div>
                    </div>
                </div><!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- container -->
    </div> <!-- content -->
    </div>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/sub_admin.js') }}"></script>
@stop

