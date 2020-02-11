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
                        <h4 class="header-title m-t-0">Add Delivery charges</h4>
                    </div>
                <!-- form start -->

                {{ Form::open(array('url' => 'admin/delivery/standard','class'=>'form-horizontal','enctype'=>'multipart/form-data','name'=>'add_package')) }}

                  <div class="box-body">

                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Select Type<span class="star_important">*</span></label>
                          <div class="col-md-6">
                             <select name="type"  onchange="get_delivery(this.value)" class="form-control">
							    <option value="standard">Standard</option>
							    <option value="express">Express</option>
							 </select>
                              <div class="error-message">{{ $errors->first('type') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->

                  <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Radius<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" id="radius" name="radius" value="{{$data->radius}}" class="form-control"  placeholder="Radius">
                              <div class="error-message">{{ $errors->first('radius') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->

				  <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Radius Charge<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" id="radius_charge" name="radius_charge"  value="{{$data->radius_charge}}" class="form-control"  placeholder="Radius Charge">
                              <div class="error-message">{{ $errors->first('radius_charge') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->

				  <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Out of radius charge<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" id="out_of_radius_charge" name="out_of_radius_charge" value="{{$data->out_of_radius_charge}}" class="form-control"  placeholder="Out Of Radius Charge">
                              <div class="error-message">{{ $errors->first('out_of_radius_charge') }}</div>
                          </div>
                      </div> 
                  </div><!-- /.box-body -->
				  
				  <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Min order<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" id="min_order" name="min_order" value="{{$data->min_order}}" class="form-control"  placeholder="Min Order">
                              <div class="error-message">{{ $errors->first('min_order') }}</div>
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
    <script>
	BASE_URL="{{URL::to('/')}}";
	</script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>

    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/delivery.js') }}"></script>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



    <script>

  $( function() {

    $( "#start_date" ).datepicker();

     $( "#end_date" ).datepicker();

  } );

  </script>



 @stop

