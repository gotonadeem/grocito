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
                        <h4 class="header-title m-t-0">Add Section</h4>
                    </div>
                <!-- form start -->
                {{ Form::open(array('url' => 'admin/help/store-help','class'=>'form-horizontal','enctype'=>'multipart/form-data','name'=>'add_faq')) }}
                  <div class="box-body">
				   
				     <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Language<span class="star_important">*</span></label>
                          <div class="col-md-6">
                               <select name="language" class="form-control">
							     <option value="eng">ENG</option>
							     <option value="hindi">HINDI</option>
							   </select>
                              <div class="error-message">{{ $errors->first('language') }}</div>
                          </div>
                      </div>
					  
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Title<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" name="title" value="{{ old('title')}}" class="form-control"  placeholder="Enter Name">
                              <div class="error-message">{{ $errors->first('title') }}</div>
                          </div>
                      </div> 
					  
					  <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Video Code<span class="star_important">*</span></label>
                          <div class="col-md-6">
                                <input type="text" name="link" value="{{ old('code')}}" class="form-control"  placeholder="Enter Video Code">
                                <div class="error-message">{{ $errors->first('code') }}</div>
                          </div>
                      </div>

                  </div><!-- /.box-body -->

                    <div class="box-footer">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                    <button type="submit" id="submit" name="add_package" class="btn btn-primary">Submit</button>
                    
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
 @stop
