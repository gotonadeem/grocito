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
                        <h4 class="header-title m-t-0">Edit Team</h4>
                    </div>
                    <a href="{{ URL::to('admin/team/team-list') }}" class="pull-right btn btn-info btn-sm" > View All</a>
                    <!-- form start -->
                    {{ Form::model($team,array('url' => 'admin/team/updateTeam/'.$team->id,'enctype'=>'multipart/form-data','class'=>'form-horizontal')) }} <div class="box-body">
                        <div class="form-group ">
                            <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-6">
                                {{Form::text('name',empty($team->name) ? '' : $team->name,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Enter Name'])}}
                                <div class="error-message">{{ $errors->first('name') }}</div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="inputEmail3" class="col-sm-3 control-label">Position</label>
                            <div class="col-sm-6">
                                {{Form::text('position',empty($team->position) ? '' : $team->position,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Enter position'])}}
                                <div class="error-message">{{ $errors->first('position') }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="col-sm-3 control-label">Image</label>
                            <div class="col-sm-6">
                                <?PHP
                                $img=empty($team->image) ? '':$team->image;
                                ?>
                                <img src="{{ URL::asset('public/admin/uploads/team_image/'.$img) }}" height="100" width="150">

                                <input type="file"   class="form-control" name="image" id="image">
                                <div class="error-message">{{ $errors->first('image') }}</div>
                                    <input type="hidden" value="{{ $slider->image or ''}}" name="old_img">

                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-md-3 control-label">Description<span class="star_important">*</span></label>
                            <div class="col-md-6">
                                {{Form::textarea('description',NULL,['class'=>'form-control','id'=>'editor1','placeholder'=>'Enter description'])}}
                                <div class="error-message">{{ $errors->first('description') }}</div>
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
