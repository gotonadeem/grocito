
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
            <h2 class="header-title m-t-0">Cashback</h2>
            <p class="text-muted font-13 m-b-10"></p>
           {{ Form::open(array('url' => 'admin/site-setting/update-cashback','class'=>'form-horizontal','id'=>'add_coin','name'=>'add_fcoin','files'=>true)) }}
              
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">Min. Order value</label>
                <div class="col-sm-7"> {{Form::text('min_order_value',empty($cashback->min_order_value) ? '' : $cashback->min_order_value,['class'=>'form-control','id'=>'admin_email','placeholder'=>'Minimum order value'])}}
                  <div class="error-message">{{ $errors->first('min_order_value') }}</div>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">Cashback(%)</label>
                <div class="col-sm-7"> {{Form::text('cashback_per',empty($cashback->cashback_per) ? '' : $cashback->cashback_per,['class'=>'form-control','id'=>'admin_email','placeholder'=>'Cashback %'])}}
                  <div class="error-message">{{ $errors->first('cashback_per') }}</div>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">Upto Cashback</label>
                <div class="col-sm-7"> {{Form::text('upto_cashback',empty($cashback->upto_cashback) ? '' : $cashback->upto_cashback,['class'=>'form-control','id'=>'admin_email','placeholder'=>'Upto Cashback'])}}
                  <div class="error-message">{{ $errors->first('upto_cashback') }}</div>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">Welcome Min. Order value</label>
                <div class="col-sm-7"> {{Form::text('welcome_min_order_value',empty($cashback->welcome_min_order_value) ? '' : $cashback->welcome_min_order_value,['class'=>'form-control','id'=>'admin_email','placeholder'=>'Welcome Minimum order value'])}}
                  <div class="error-message">{{ $errors->first('welcome_min_order_value') }}</div>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 form-control-label">Welcome Cashback(%)</label>
                <div class="col-sm-7"> {{Form::text('welcome_cashback_per',empty($cashback->welcome_cashback_per) ? '' : $cashback->welcome_cashback_per,['class'=>'form-control','id'=>'admin_email','placeholder'=>'Welcome Cashback %'])}}
                  <div class="error-message">{{ $errors->first('welcome_cashback_per') }}</div>
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
@stop 