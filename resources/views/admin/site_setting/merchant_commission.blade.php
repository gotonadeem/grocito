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
                        <h4 class="header-title m-t-0">Merchant Wallet Limit</h4>
                        <p class="text-muted font-13 m-b-10"></p>
                        <div class="p-70">
                            {{ Form::open(array('url' => 'admin/site-setting/merchant-commission','class'=>'form-horizontal','id'=>'add_coin','name'=>'add_fcoin','files'=>true)) }}
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Level1</label>
                                <div class="col-sm-7">
                                    {{Form::text('level[]', $data[0]->value,['class'=>'form-control','id'=>'merchant_key','placeholder'=>'Level1'])}}
                                    <div class="error-message">{{ $errors->first('merchant_key') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Level2</label>
                                <div class="col-sm-7">
                                    {{Form::text('level[]',$data[1]->value,['class'=>'form-control','id'=>'site_name','placeholder'=>'Level2'])}}
                                    <div class="error-message">{{ $errors->first('salt') }}</div>
                                </div>
                            </div>
							
							<div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">level3</label>
                                <div class="col-sm-7">
                                    {{Form::text('level[]',$data[2]->value,['class'=>'form-control','id'=>'site_name','placeholder'=>'Level3'])}}
                                    <div class="error-message">{{ $errors->first('salt') }}</div>
                                </div>
                            </div>
							
							<div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Level4</label>
                                <div class="col-sm-7">
                                    {{Form::text('level[]',$data[3]->value,['class'=>'form-control','id'=>'site_name','placeholder'=>'Level4'])}}
                                    <div class="error-message">{{ $errors->first('salt') }}</div>
                                </div>
                            </div>
							
							<div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Level5</label>
                                <div class="col-sm-7">
                                    {{Form::text('level[]',$data[4]->value,['class'=>'form-control','id'=>'site_name','placeholder'=>'level5'])}}
                                    <div class="error-message">{{ $errors->first('salt') }}</div>
                                </div>
                            </div>
							<div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Saleplus Commission(%)</label>
                                <div class="col-sm-7">
                                    {{Form::text('commission',$data[0]->commission,['class'=>'form-control','id'=>'site_name',
									'placeholder'=>'Saleplus Commission'])}}
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
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/general_setting.js') }}"></script>
@stop

