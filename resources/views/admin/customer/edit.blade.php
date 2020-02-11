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
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Edit User Details</h5>
						<div class="text-right"><a href="{{ URL::to('admin/user/user-list') }}" class="btn btn-info">Back</a>
                        <div class="ibox-tools">
						 
                            
                        </div>
                    </div>
                   
				    {{ Form::model($user,array('url' => 'admin/user/edit/'.$user->id,'class'=>'form-horizontal')) }}
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Name<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {{Form::text('username',NULL,['class'=>'form-control','id'=>'username','placeholder'=>'Enter username'])}}
                                        <div class="error-message">{{ $errors->first('username') }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Email<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {{Form::text('email',$user->email,['class'=>'form-control','id'=>'email','placeholder'=>'Enter email'])}}
                                        <div class="error-message">{{ $errors->first('email') }}</div>

                                    </div>
                                </div>
                               <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Mobile<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {{Form::text('mobile',$user->mobile,['class'=>'form-control','id'=>'mobile','placeholder'=>'Enter mobile'])}}
                                        <div class="error-message">{{ $errors->first('mobile') }}</div>

                                    </div>
                                </div>

                               
                                <div class="form-group row">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <button type="submit" name="add_user" value="add_user"  class="btn btn-primary waves-effect waves-light">
                                            Update
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
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/user.js') }}"></script>
@stop
