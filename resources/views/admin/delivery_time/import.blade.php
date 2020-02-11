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
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <div class="row" style="margin-bottom:15px;">
                        <div class="col-xs-6">
                            <h3>Import CSV</h3>
                        </div>
                        <div class="col-xs-6 text-right">
                        <a href="{{ URL::to('admin/city/index') }}" class="btn btn-info">Back</a>
                        </div>
                        </div>
                        {{ Form::open(array('url' =>'admin/city/store_import','class'=>'form-horizontal','enctype'=>'multipart/form-data','method'=>'post')) }}
                              <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 form-control-label">State</label>
                                        <div class="col-sm-8">
                                            <select class="form-control state_list" name="state_id" id="state_list" onchange="get_city(this.value)">
                                                <option value="">Select State</option>
                                                <?PHP foreach($stateList as $vs): ?>
                                                <option value="<?=$vs->id;?>"><?=$vs->name;?></option>
                                                <?PHP endforeach; ?>
                                            </select>
                                            <div class="error-message">{{ $errors->first('state_id') }}</div>
                                        </div>
                                    </div>
                                </div>

                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Select City<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                          <select name="city_id" id="city_list" class="form-control city_list">
										  <option value="">Select City</option>

										  </select>
                                        <div class="error-message">{{ $errors->first('city_id') }}</div>
                                    </div>
                                </div>
                            </div>
							<div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Import<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                         <input type="file" name="csv">
                                        <div class="error-message">{{ $errors->first('csv') }}</div>
                                    </div>
                                </div>
                                <a href="{{URL::asset('public/uploads/city_csv/demo/demo.csv')}}" class="btn btn-primary download-btn" download >Demo csv</a>
                            </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" name="add_user" value="add_user" class="btn btn-primary waves-effect waves-light">
                                    Submit
                                </button>

                            </div>
                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    
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
        <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/builder.js') }}"></script>
        <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/city.js') }}"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
        <script type="text/javascript">

            $(".state_list").select2();
            $(".city_list").select2();
            $(".delivery_pincode").select2();
        </script>
    </div>
@stop
