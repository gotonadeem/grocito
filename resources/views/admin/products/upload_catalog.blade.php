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
                    <h3>Upload Catalog</h3>
                    <div class="text-right"><a href="{{ URL::to('admin/product/product-list') }}" class="btn btn-info">Back</a>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    {{ Form::open(array('url' => 'admin/product/store-catalog-image','class'=>'form-horizontal','id'=>'add_product','name'=>'add_product',"enctype"=>"multipart/form-data")) }}
                     <input type="hidden" name="product_id" value="{{$data->id}}">
                     <div class="col-sm-12">
                         <div class="form-group row">
                                 <label class="col-sm-4 form-control-label">Catalog Images</label>
                                 <input type = "file" name = "image" multiple = "multiple" class = "form-control" />
								 <div class="error-message">{{ $errors->first('image') }}</div>
								 @if($data->image)
							 <img class="img-thumb" src="{{URL::asset('public/uploads/seller/catalog/'.$data->image)}}">
								 @endif
                         </div>
						 
                     </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type = "submit" class = "btn btn-primary waves-effect waves-light" value = "Submit" />
                        </div>
                    </div>
                    {{ Form::close() }}
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
     </script>
	 <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/developer/page_js/product.js') }}"></script>
@stop
