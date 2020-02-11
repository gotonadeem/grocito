<?php
/**
 * Created by PhpStorm.
 * User: wingstud
 * Date: 10/8/17
 * Time: 12:49 PM
 */
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>Manage Size</h3>
                    <div class="text-right">
                        <div class="ibox-tools">
                        </div>
                    </div>
                    {{ Form::open(array('url' => 'admin/product/store-size','class'=>'form-horizontal','id'=>'add_product','name'=>'add_product',"enctype"=>"multipart/form-data")) }}
                    					 
					<div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Select Size<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                              <input type="text" name="name" class="form-control" placeholder="Enter Size">
                        <div class="error-message">{{ $errors->first('name') }}</div>
                            </div>
                        </div>
                    </div>
					
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="submit" name="go"  class = "cvf_upload_btn btn btn-primary waves-effect waves-light" value = "Submit" />
                        </div>
                    </div>
                    {{ Form::close() }}
                   <table class="table">
				   <tr>
				     <th>Id</th>
				     <th>Name</th>
				     <th>Action</th>
				   </tr>
				    <?PHP $i=1; ?>
				    @foreach($data as $vs)
				    
				   <tr>
				     <td>{{$i}}</td>
				     <td>{{$vs->name}}</td>
				     <td><a onclick="deleteItem({{$vs->id}})" href="javascript:void(0)"><i class="fa fa-trash"></i></a></td>
				   </tr>
				   <?PHP $i++; ?>
				   @endforeach
				   
				   </table>
				</div>
            </div>
        </div>
    </div>
</div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- Custom and plugin javascript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
	 <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
	   <script>
      $('#seller').select2({
                placeholder : 'Please select Seller',
                tags: true
            });    
    CKEDITOR.replace( 'description');
        CKEDITOR.replace( 'extra_config_details');
        var delete_img="{{ URL::asset('public/admin/images/delete-btn.png') }}";
        var loading_img="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}";
    </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/developer/page_js/size.js') }}"></script>
     
	@stop
