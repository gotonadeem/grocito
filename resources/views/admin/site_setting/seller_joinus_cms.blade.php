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
            <h2 class="header-title m-t-0">Seller Join Us Cms</h2>
            <p class="text-muted font-13 m-b-10"></p>
           {{ Form::open(array('url' => 'admin/site-setting/seller-joinus-cms-store','class'=>'form-horizontal','id'=>'add_coin','name'=>'add_fcoin','files'=>true)) }}

                    <div class="form-group">
                        <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Description</label>
                        <div class="col-md-6">
                            {{Form::textarea('description',empty($setting->description) ? '' : $setting->description,['class'=>'form-control','id'=>'description','required'=>'required','placeholder'=>'Enter description'])}}
                            <div class="error-message">{{ $errors->first('description') }}</div>
                        </div>
                    </div>
			 
			  
              <div class="form-group row">
                <div class="col-sm-8 col-sm-offset-4">
                  <button type="submit" name="add_general_setting" value="general_setting"  class="btn btn-primary waves-effect waves-light"> Submit </button>
                  <!-- <button type="reset" class="btn btn-default waves-effect m-l-5"> Cancel </button> -->
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
@include('admin.includes.admin_right_sidebar')
@include('admin.includes.admin_footer')
<script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/sub_admin.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'description' );
</script>
@stop 