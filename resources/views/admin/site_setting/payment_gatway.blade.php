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
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Payment Gatway Setting</h4>
                        <p class="text-muted font-13 m-b-10"></p>
                        <div class="p-70">
                            {{ Form::open(array('url' => 'admin/site-setting/payumoney-setting','class'=>'form-horizontal','id'=>'add_coin','name'=>'add_fcoin','files'=>true)) }}
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Maerchant Key</label>
                                <div class="col-sm-7">
                                    {{Form::text('merchant_key', empty($setting->merchant_key) ? '' : $setting->merchant_key,['class'=>'form-control','id'=>'merchant_key','placeholder'=>'Merchant Key'])}}
                                    <div class="error-message">{{ $errors->first('merchant_key') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Merchant ID</label>
                                <div class="col-sm-7">
                                    {{Form::text('salt',empty($setting->salt) ? '' : $setting->salt,['class'=>'form-control','id'=>'site_name','placeholder'=>'Salt'])}}
                                    <div class="error-message">{{ $errors->first('salt') }}</div>
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


            </div> <!-- container -->
        </div> <!-- container -->
    </div> <!-- content -->
    </div>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/general_setting.js') }}"></script>
@stop

