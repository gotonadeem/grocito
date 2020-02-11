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
                        <h5>List of Verified Product</h5>
						<!--<div class="col-md-4">Filter By Category &nbsp;&nbsp; 
						<select class="form-control" name="filter_by_category" id="filter_by_category">
						  <option value="">Select Category</option>
						  @foreach($category_list as $vs)
						    <option value="{{$vs->id}}">{{$vs->main_category->name}}</option>
						  @endforeach
						</select>
						</div>-->
                        <div class="text-right"><a href="{{ URL::to('admin/product/create-product') }}" class="btn btn-info">Add New</a>
                        </div>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table id="property-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Seller Name</th>
                                        <th>Seller Mobile</th>
                                        <th>Category</th>
                                        <th>Product Name</th>
                                        <th>MRP Price</th>
                                        <th>Sell Price</th>
                                        <th>Created At</th>
                                        <th>Action</th>
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
    </div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    @include('admin.includes.admin_footer_inner')
    <!-- Page-Level Scripts -->
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/product.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"P-Name,Watermark");
	</script>
@stop