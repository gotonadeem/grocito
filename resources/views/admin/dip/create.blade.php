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
        <div class="content" style="
    padding: 0 0px 0px 0px;
    margin-top: 29px;">
            <div class="container">
                <!-- end row -->
                <br>
                <div class="row coin-add" style="background:#fff; padding:10px;">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Add Dip</h4>
                    </div>
                <!-- form start -->
                {{ Form::open(array('url' => 'admin/dip/storeDip','class'=>'form-horizontal','enctype'=>'multipart/form-data','name'=>'add_slider')) }}
                  <div class="box-body">



                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Image</label>
                          <div class="col-sm-7">
                              <input type="file"  required class="form-control" name="image" id="image">
                              <div class="error-message">{{ $errors->first('image') }}</div>

                          </div>
                      </div>
                    
					            <div class="form-group">
                          <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Link Category</label>
                          <div class="col-md-6">
                             <select class="form-control" name="link">
                                     <option value="">Select Category</option>
                                    <?PHP foreach($category_list as $vs): ?>
                                       <option value="<?=$vs->slug?>"><?=$vs->name?></option>
                                    <?PHp endforeach; ?>
                                    </select>
                              <div class="error-message">{{ $errors->first('link') }}</div>
                          </div>
                      </div>

                  </div><!-- /.box-body -->

                    <div class="box-footer">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                    <button type="submit" id="submit" name="add_slider" class="btn btn-primary">Submit</button>
                    
                  </div>
                  </div>
                {{ Form::close() }}
               </div> <!-- container -->
        </div> <!-- container -->
    </div> <!-- content -->
    </div>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/sub_admin.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'title' );
    </script>
 @stop
