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
                        <h3>Add New Sub Category</h3>
                        <div class="text-right"><a href="{{ URL::to('admin/subcategory/subcategory-list') }}" class="btn btn-info">Back</a>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        {{ Form::open(array('url' =>'admin/subcategory/store-subcategory','class'=>'form-horizontal','enctype'=>'multipart/form-data','method'=>'post')) }}
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Parent Category<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="category_id">
                                     <option value="">Select Category</option>
                                    <?PHP foreach($category_list as $vs): ?>
                                       <option value="<?=$vs->id?>"><?=$vs->name?></option>
                                    <?PHp endforeach; ?>
                                    </select>
                                    <div class="error-message">{{ $errors->first('category_id') }}</div>
                                </div>
                            </div>
                        </div> 
						<div class="col-sm-12">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Name<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" value="{{ old('name')}}" class="form-control"  placeholder="Enter Name">
                                    <div class="error-message">{{ $errors->first('name') }}</div>
                                </div>
                            </div>
                        </div>
						
						
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Icon</label>
                                    <div class="col-sm-8">
                                        <input type="file" name="image" class="form-control">
                                        <div class="error-message">{{ $errors->first('image')}}</div>
                                    </div>
                                </div>
                            </div>		
							
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Status<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="radio">
                                        <label>
                                            <input  name="status" <?php if(old('status')=='1'){echo "checked";} else{echo "checked";}?> value="1"  type="radio">
                                            Enable
                                        </label>
                                        <label>
                                            <input name="status" <?php if(old('status')=='0'){echo "checked";}?> value="0" type="radio">
                                            Disable
                                        </label>
                                    </div>
                                    <div class="error-message">{{ $errors->first('status') }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" name="add_user" value="add_user" class="btn btn-primary waves-effect waves-light">
                                    Submit
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
