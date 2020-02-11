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
                        <h3>Sub Category Details</h3>
                        <div class="text-right"><a href="{{ URL::to('admin/subcategory/subcategory-list') }}" class="btn btn-info">Back</a>
                            <div class="ibox-tools">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Main Category Name<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                {{ $category->main_category_name }}
                            </div>
                        </div>
                    </div>

                    <br><br>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Name<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                {{ $category->name }}
                            </div>
                        </div>
                    </div>
					
					 <div class="col-sm-12">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Icon<span class="text-danger">*</span></label>
                                <div class="col-sm-8">                                     									 <img src="{{URL::asset('public/admin/uploads/category/'.$category->image)}}" style="height:100px;width:100px;">                                   
                         
                                </div>
                            </div>
                        </div>
						<div class="col-sm-12">
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-4 form-control-label">Status<span class="text-danger">*</span></label>
								<div class="col-sm-8">
									<div class="radio">
										@if($category->status ==1)
											<b>Active</b>
										@else
											<b>InActive</b>
										@endif
									</div>
								</div>
							</div>
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
            BASE_URL='{{ URL::to('/') }}';            var category_id='{{$category->id }}';
        </script>    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/sub_category.js') }}"></script>
    </div>
@stop
