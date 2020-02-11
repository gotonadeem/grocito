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
            <div class="container" style="background: white;">
                <!-- end row -->
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Bank Details</h4>
                        <p class="text-muted font-13 m-b-10"></p>
                        <div class="p-70">
                            {{ Form::model($setting,array('url' => 'admin/bank/bank-store/'.'2','class'=>'form-horizontal','name'=>'add_bank')) }} <div class="box-body">

{{--
                            {{ Form::open(array('url' => 'admin/bank/bank-store/'.'2','class'=>'form-horizontal','id'=>'add_bank','name'=>'add_bank','files'=>true)) }}
--}}
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Account Holder Name</label>
                                <div class="col-sm-7">
                                    {{Form::text('account_holder_name', empty($setting->account_holder_name) ? '' : $setting->account_holder_name,['class'=>'form-control','id'=>'site_url','placeholder'=>'Account Holder Name'])}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Account Number</label>
                                <div class="col-sm-7">
                                    {{Form::text('account_no', empty($setting->account_no) ? '' : $setting->account_no,['class'=>'form-control','id'=>'site_url','placeholder'=>'Account Number'])}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">IFSC Code</label>
                                <div class="col-sm-7">
                                    {{Form::text('ifsc', empty($setting->ifsc) ? '' : $setting->ifsc,['class'=>'form-control','id'=>'site_url','placeholder'=>'IFSC Code'])}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Bank Name</label>
                                <div class="col-sm-7">
                                    {{Form::text('bank_name', empty($setting->bank_name) ? '' : $setting->bank_name,['class'=>'form-control','id'=>'site_url','placeholder'=>'Bank Name'])}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <button type="submit" name="add_bank" value="general_setting"  class="btn btn-primary waves-effect waves-light">
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
            </div> <!-- container -->
        </div> <!-- container -->
    </div> <!-- content -->
    </div>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/general_setting.js') }}"></script>
@stop