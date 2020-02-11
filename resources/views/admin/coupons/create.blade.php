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
                        <h4 class="header-title m-t-0">Add Coupon</h4>
                        <div class="text-right"><a href="{{ URL::to('admin/coupon/coupon-list') }}" class="btn btn-info">Back</a>
                            <div class="ibox-tools">
                            </div>
                        </div>
                    </div>

                <!-- form start -->
                {{ Form::open(array('url' => 'admin/coupon/store','class'=>'form-horizontal','enctype'=>'multipart/form-data','name'=>'add_package')) }}
                  <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Coupon Code<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" name="code" value="{{ old('code')}}" class="form-control"  placeholder="Enter Code">
                              <div class="error-message">{{ $errors->first('code') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->
                  <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Start Date<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" id="start_date" name="start_date" value="{{ old('start_date')}}" class="form-control"  placeholder="Start Date">
                              <div class="error-message">{{ $errors->first('start_date') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->
                   <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">End Date<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" id="end_date" name="end_date" value="{{ old('end_date')}}" class="form-control"  placeholder="End Date">
                              <div class="error-message">{{ $errors->first('end_date') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->

                   <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Discount Amount<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" name="discount_amount" value="{{ old('discount_amount')}}" class="form-control"  placeholder=" Discount amount">
                              <div class="error-message">{{ $errors->first('discount_amount') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->

                   <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Discount Unit<span class="star_important">*</span></label>
                          <div class="col-md-6">
                             <select class="form-control" name="discount_unit">
                                <option name="percent">%</option>
                                <option name="fixed">Fixed</option>
                             </select>
                              <div class="error-message">{{ $errors->first('discount_unit') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->

                   <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">No of Usage<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" name="no_of_usage" value="{{ old('no_of_usage')}}" class="form-control"  placeholder="No Of Usage">
                              <div class="error-message">{{ $errors->first('no_of_usage') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->

                   <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Usage Per User<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" name="usage_per_user" value="{{ old('usage_per_user')}}" class="form-control"  placeholder="Uses Per User">
                              <div class="error-message">{{ $errors->first('usage_per_user') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->

                   <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Min Order Amount<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" name="min_ord_amount" value="{{ old('min_ord_amount')}}" class="form-control"  placeholder="Min order amount">
                              <div class="error-message">{{ $errors->first('min_ord_amount') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->

                   <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Status<span class="star_important">*</span></label>
                          <div class="col-md-6">
                            <select name="status" class="form-control">
                              <option value="0">InActive</option>
                              <option value="1">Active</option> 
                            </select>
                              <div class="error-message">{{ $errors->first('status') }}</div>
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
  $( function() {
    $( "#start_date" ).datepicker();
     $( "#end_date" ).datepicker();
  } );
  </script>

 @stop
