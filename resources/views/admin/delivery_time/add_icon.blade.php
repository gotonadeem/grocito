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
                        <h4 class="header-title m-t-0">Add City Icon</h4>
                    </div>
                    <a href="{{ URL::to('admin/city/index') }}" class="pull-right btn btn-info btn-sm" ><i class="fa fa-info"></i> Back</a>
                    <!-- form start -->
                     {{ Form::model($cityData,array('url' => 'admin/city/store-icon', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')) }}
                   <div class="box-body">

                       <div class="form-group">
                           <label for="exampleInputEmail1" class="col-sm-3 form-control-label">City Icon</label>
                           <div class="col-sm-7">


                               <input type="file" class="form-control" name="icon" id="icon">
                               <div class="error-message">{{ $errors->first('icon') }}</div>
                                   <input type="hidden" value="{{ $cityData->icon or ''}}" name="old_img">

                                   <input type="hidden" value="{{ $cityData->id}}" name="city_id">
                               <?PHP
                               $img=empty($cityData->icon) ? '':$cityData->icon;
                                   if($img){

                               ?>
                               <input type="hidden" value="{{ $cityData->icon}}" name="icon">
                               <img src="{{ URL::asset('public/admin/uploads/city_icon/'.$img) }}" height="40" width="40">
                               <?php } ?>
                           </div>
                       </div>



                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button type="submit" id="submit" value="submit" class="btn btn-primary">Submit</button>
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
