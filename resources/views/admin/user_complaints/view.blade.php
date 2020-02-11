<?php
   //
?>
@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3>View Complaint Data</h3>
                        <div class="text-right"><a href="{{ URL::to('admin/site-setting/user-complaints') }}" class="btn btn-info">Back</a>
                            <div class="ibox-tools">
                            </div>
                        </div>
                    </div>
                    <br><br>
                         <div class="col-sm-12">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">User <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    {{ $data->user->username }}
                                </div>
                            </div>
                        </div>
						
						<div class="col-sm-12">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Complaint ID<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    #{{ $data->complaint_id }}
                                </div>
                            </div>
                        </div>
                       <!--  <div class="col-sm-12">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Status<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    {{ $data->status }}
                                </div>
                            </div>
                        </div> -->
						
						<div class="col-sm-12">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Reply<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    {{ $data->reply }}
                                </div>
                            </div>
                        </div>
						
						<div class="col-sm-12">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Message<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    {{ $data->complaint_message }}
                                </div>
                            </div>
                        </div>
						                   
            </div>
            <div class="ibox-content">
                        <h4></h4>
                        <p class="text-muted font-13 m-b-10"></p>
                        <div class="p-70">
                            {{ Form::open(array('url' => 'admin/site-setting/store-user-complaints','class'=>'form-horizontal','id'=>'add_coin','name'=>'add_fcoin','files'=>true)) }}
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Reply Text</label>
                                <div class="col-sm-10">
                                    {{Form::textarea('reply', empty($data->reply) ? '' : $data->reply,['class'=>'form-control','id'=>'reply','placeholder'=>'reply'])}}
                                    <div class="error-message">{{ $errors->first('reply') }}</div>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="form-group row">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <button type="submit" name="bank_detail" value="Account Holder Name"   class="btn btn-primary waves-effect waves-light">
                                        Update
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
         <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/category.js') }}"></script>
         <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'description' );
    </script>

	
     </div>
 </div>
@stop
