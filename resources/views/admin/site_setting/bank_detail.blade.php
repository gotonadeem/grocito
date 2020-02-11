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
            <h4 class="header-title m-t-0">General Setting</h4>
                        <p class="text-muted font-13 m-b-10"></p>
                        <div class="p-70">
                            {{ Form::open(array('url' => 'admin/site-setting/bank-detail','class'=>'form-horizontal','id'=>'add_coin','name'=>'add_fcoin','files'=>true)) }}
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Bank Name</label>
                                <div class="col-sm-7">
                                    {{Form::text('bank_name', empty($setting->bank_name) ? '' : $setting->bank_name,['class'=>'form-control','id'=>'bank_name','placeholder'=>'Bank name'])}}
                                    <div class="error-message">{{ $errors->first('bank_name') }}</div>
                                </div>
                            </div>
                             <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Account Number</label>
                                <div class="col-sm-7">
                                    {{Form::text('account_no', empty($setting->account_no) ? '' : $setting->account_no,['class'=>'form-control','id'=>'site_url','placeholder'=>'Account Number'])}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">IFSC</label>
                                <div class="col-sm-7">
                                    {{Form::text('ifsc',empty($setting->ifsc) ? '' : $setting->ifsc,['class'=>'form-control','id'=>'bank_name','placeholder'=>'Ifsc'])}}
                                    <div class="error-message">{{ $errors->first('ifsc') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Account Holder Name</label>
                                <div class="col-sm-7">
                                    {{Form::text('account_holder_name',empty($setting->account_holder_name) ? '' : $setting->account_holder_name,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Account Holder Name'])}}
                                    <div class="error-message">{{ $errors->first('account_holder_name') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Branch Name</label>
                                <div class="col-sm-7">
                                    {{Form::text('branch_name',empty($setting->branch_name) ? '' : $setting->branch_name,['class'=>'form-control','id'=>'branch_name','placeholder'=>'Branch Name'])}}
                                    <div class="error-message">{{ $errors->first('branch_name') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <button type="submit" name="bank_detail" value="Account Holder Name"   class="btn btn-primary waves-effect waves-light">
                                        Submit
                                    </button>
                                    <button type="reset"
                                            class="btn btn-default waves-effect m-l-5">
                                        Cancel
                                    </button>
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