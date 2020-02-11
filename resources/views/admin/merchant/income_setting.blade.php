@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3 >Income Setting</h3> 
                        <div class="row">
						<div class="ibox-tools">
                        </div>
                        
				    {{ Form::open(array('url' =>'admin/delivery-boy/income-setting','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'add_seller','name'=>'add_seller')) }}
                                 <div class="col-sm-12">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Amount(Per KM)<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
                                         <input type="text" autocomplete="off" class="form-control" name="per_km" id="per_km" value="{{$data->per_km}}" placeholder="Per Km">
                                         <div class="error-message">{{ $errors->first('per_km') }}</div>
                                     </div>
                                 </div>
								 </div>    
								 
								 <div class="col-sm-12">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Base Income<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
                                         <input type="text" autocomplete="off" class="form-control" name="base_income" id="base_income" value="{{$data->base_income}}" placeholder="Last Name">
                                         <div class="error-message">{{ $errors->first('base_income') }}</div>
                                     </div>
                                 </div>
								 </div>  
								 <div class="col-sm-12">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">COD Limit<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
                                         <input type="text" autocomplete="off" class="form-control" name="cod_limit" id="cod_limit" value="{{$data->cod_limit}}" placeholder="cod limit">
                                         <div class="error-message">{{ $errors->first('cod_limit') }}</div>
                                     </div>
                                 </div>
								 </div>   
								 
								 
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 form-control-label">Bonus<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" autocomplete="off" class="form-control" name="bonus" id="bonus"  placeholder="Bonus" value="{{$data->bonus}}">
                                            <div class="error-message" id="bonus_msg">{{ $errors->first('bonus') }}</div>
                                        </div>
                                    </div>
                                </div>
							   
					
								  
								  <div class="col-sm-12">	 
								
                                     <div class="form-group row">
                                         <div class="col-sm-12">
                                             <button type="submit" name="add_user"  class="btn pull-right btn-primary waves-effect waves-light pull-right submit_margin">
                                                 Submit
                                             </button>
                                            
                                         </div>
                                     </div>
                                 {{ Form::close() }}
                                 </div>
                                 </div>
            </div>
        </div>
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
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/seller.js') }}"></script>
@stop
