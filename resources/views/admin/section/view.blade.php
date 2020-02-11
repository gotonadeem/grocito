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
                    <h3>View Section</h3>
                    <div class="text-right"><a href="{{ URL::to('admin/section/section-list') }}" class="btn btn-info">Back</a>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    {{ Form::open(array('url' =>'admin/notice/store-notice','class'=>'form-horizontal','enctype'=>'multipart/form-data','method'=>'post')) }}
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Title<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                               <?Php echo $data->title; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Description<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                 <?Php echo $data->description; ?>
                            </div>
                        </div>
                    </div>
                  
                    <!-- /.box-body -->
                   
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
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
	<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'description' );
    </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/builder.js') }}"></script>
</div>
@stop
