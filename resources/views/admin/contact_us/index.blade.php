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
        <div class="content" style="
    padding: 0 0px 0px 0px;
    margin-top: 29px;">
            <div class="container">
                <!-- end row -->
                <br>
                <div class="row coin-add" style="background:#fff; padding:10px;">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Help Section</h4>
                    </div>
                <!-- form start -->
                        <div class="p-70">
                            {{ Form::open(array('url' => 'admin/site-setting/contact-store','class'=>'form-horizontal','id'=>'add_contact_us','name'=>'add_contact_us','files'=>true)) }}
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Mobile No</label>
                                <div class="col-sm-7">
                                    {{Form::text('mobile', empty($contact_us->mobile) ? '' : $contact_us->mobile,['class'=>'form-control','id'=>'mobile','placeholder'=>'Help Mobile No'])}}
                                    <div class="error-message">{{ $errors->first('mobile') }}</div>
                                </div>
                            </div>
							<div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Email</label>
                                <div class="col-sm-7">
                                    {{Form::text('email', empty($contact_us->email) ? '' : $contact_us->email,['class'=>'form-control','id'=>'email','placeholder'=>'Help Email'])}}
                                    <div class="error-message">{{ $errors->first('email') }}</div>
                                </div>
                            </div>
							<div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Time</label>
                                <div class="col-sm-7">
                                    {{Form::text('time', empty($contact_us->time) ? '' : $contact_us->time,['class'=>'form-control','id'=>'time','placeholder'=>'Help Time'])}}
                                    <div class="error-message">{{ $errors->first('time') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <button type="submit" name="add_general_setting" value="general_setting"  class="btn btn-primary waves-effect waves-light">
                                        Submit
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
        </div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
        <script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>
        <script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
        <script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ URL::asset('public/admin/js/plugins/dataTables/datatables.min.js') }}"></script>
        <!-- Custom and plugin javascript -->
        <script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
        <script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
        <!-- Page-Level Scripts -->
        <script>
            ASSET_URL = '{{ URL::asset('public') }}/';
            BASE_URL='{{ URL::to('/') }}';
        </script>
        <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
        <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/builder.js') }}"></script>
    </div>
@stop