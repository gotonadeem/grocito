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
                        <h4 class="header-title m-t-0">Edit User Notification</h4>
                    </div>
                    <a href="{{ URL::to('admin/site-setting/user-notification') }}" class="pull-right btn btn-info btn-sm" ><i class="fa fa-info"></i> View All</a>
                    <!-- form start -->
                     {{ Form::model($notification,array('url' => 'admin/site-setting/update-notification/'.$notification->id, 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')) }}
                   <div class="box-body">
                       <div class="form-group">
                           <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Image</label>
                           <div class="col-sm-7">
                               <?PHP
                               $img=empty($notification->image) ? '':$notification->image;
                               ?>
                               <img src="{{ URL::asset('public/admin/uploads/user_notification/'.$img) }}" height="100" width="150">

                               <input type="file" class="form-control" name="image" id="image">
                               <div class="error-message">{{ $errors->first('image') }}</div>
                                   <input type="hidden" value="{{ $notification->image or ''}}" name="old_img">
                           </div>
                       </div>
                       <div class="form-group">
                           <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Title</label>
                           <div class="col-md-6">
                            <?php $tit = empty($notification->title) ? '' : $notification->title ?>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" value="{{$tit}}">
                               <div class="error-message">{{ $errors->first('title') }}</div>
                           </div>
                       </div>
                       <div class="form-group">
                           <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Description</label>
                           <div class="col-md-6">
                               {{Form::textarea('description',empty($notification->description) ? '' : $notification->description,['class'=>'form-control','id'=>'description','placeholder'=>'Enter Title'])}}
                               <div class="error-message">{{ $errors->first('description') }}</div>
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
        CKEDITOR.replace( 'description' );
    </script>
@stop
