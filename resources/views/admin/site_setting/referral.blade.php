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
            <h2 class="header-title m-t-0">Reffer & Earn</h2><br/><br/>
            <p class="text-muted font-13 m-b-10"></p>
                        
           {{ Form::open(array('url' => 'admin/site-setting/update-referral','class'=>'form-horizontal','id'=>'add_coin','name'=>'add_fcoin','files'=>true)) }}
              
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">Referrer Amount</label>
                <div class="col-sm-7"> {{Form::text('referrer_amount',empty($refferearn->referrer_amount) ? '' : $refferearn->referrer_amount,['class'=>'form-control','id'=>'admin_email','placeholder'=>'Referrer Amount'])}}
                  <div class="error-message">{{ $errors->first('referrer_amount') }}</div>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">Referral Amount</label>
                <div class="col-sm-7"> {{Form::text('referral_amount',empty($refferearn->referral_amount) ? '' : $refferearn->referral_amount,['class'=>'form-control','id'=>'admin_email','placeholder'=>' Referral Amount'])}}
                  <div class="error-message">{{ $errors->first('referral_amount') }}</div>
                </div>
              </div>
              <div class="form-group row">
                           <label for="exampleInputEmail1" class="col-sm-4 form-control-label">Description</label>
                           <div class="col-md-7">
                               {{Form::textarea('referral_description',empty($refferearn->referral_description) ? '' : $refferearn->referral_description,['class'=>'form-control','id'=>'referral_description','placeholder'=>'Referral description'])}}
                               <div class="error-message">{{ $errors->first('referral_description') }}</div>
                           </div>
              </div>                     
        
              <div class="form-group row">
                <div class="col-sm-8 col-sm-offset-4">
                  <button type="submit" name="add_general_setting" value="general_setting"  class="btn btn-primary waves-effect waves-light"> Update </button>
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
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/cashback.js') }}"></script> 
<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'referral_description' );
    </script>
@stop 