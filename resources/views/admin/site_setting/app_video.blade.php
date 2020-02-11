@extends('admin.layout.admin')
@section('content') 
<!-- ============================================================== -->
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                   
                </div>
                <div class="ibox-content">
            <h4 class="header-title m-t-0">App Video</h4><br>
            <p class="text-muted font-13 m-b-10"></p>
           {{ Form::open(array('url' => 'admin/site-setting/app-video-store','class'=>'form-horizontal','id'=>'add_coin','name'=>'add_fcoin','files'=>true)) }}
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">Hindi Title</label>
                <div class="col-sm-7"> {{Form::text('video_hindi_title',empty($setting->video_hindi_title) ? '' : $setting->video_hindi_title,['class'=>'form-control','id'=>'video_hindi_title','placeholder'=>'App Video Hindi Title'])}}
                  <div class="error-message">{{ $errors->first('video_hindi_title') }}</div>
                </div>
              </div>
			  <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">English Title</label>
                <div class="col-sm-7"> {{Form::text('video_english_title',empty($setting->video_english_title) ? '' : $setting->video_english_title,['class'=>'form-control','id'=>'video_english_title','placeholder'=>'App Video English Title'])}}
                  <div class="error-message">{{ $errors->first('video_english_title') }}</div>
                </div>
              </div>
			  <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">Hindi Video Code</label>
                <div class="col-sm-7"> {{Form::text('app_hindi_video',empty($setting->app_hindi_video) ? '' : $setting->app_hindi_video,['class'=>'form-control','id'=>'app_hindi_video','placeholder'=>'App Hindi Video Code'])}}
                  <div class="error-message">{{ $errors->first('app_hindi_video') }}</div>
                </div>
              </div>
			  <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">English Video Code</label>
                <div class="col-sm-7"> {{Form::text('app_english_video',empty($setting->app_english_video) ? '' : $setting->app_english_video,['class'=>'form-control','id'=>'app_english_video','placeholder'=>'App English Video Code'])}}
                  <div class="error-message">{{ $errors->first('app_hindi_video') }}</div>
                </div>
              </div>
			  
              <div class="form-group row">
                <div class="col-sm-8 col-sm-offset-4">
                  <button type="submit" name="add_general_setting" value="general_setting"  class="btn btn-primary waves-effect waves-light"> Submit </button>
                  <button type="reset" class="btn btn-default waves-effect m-l-5"> Cancel </button>
                </div>
              </div>
              {{ Form::close() }} 
          </div>
        </div>
      </div>
    </div>
    <!-- container --> 
  </div>
  <!-- container --> 
<!-- content -->
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
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/general_setting.js') }}"></script> 
@stop 