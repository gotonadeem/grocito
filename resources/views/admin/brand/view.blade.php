@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title display">
                        <div class="row">
                            <h3 class="pull-left"><a href="javascript:void(0)" class="btn btn-info button_margin_left">Warehouse Information</a></h3>
                        </div>
                        <div class="text-right"><a href="{{ URL::previous() }}" class="btn btn-info">Back</a>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">City Name</label>
                                <div class="col-sm-8">
                                    {{$warehouse->get_city->name}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Pincode</label>
                                <div class="col-sm-8">
                                    {{$warehouse->pincode}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Warehouse Name</label>
                                <div class="col-sm-8">
                                    {{$warehouse->name}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Warehouse Pincode</label>
                                <div class="col-sm-8">
                                    {{$warehouse->warehouse_pincode}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Address</label>
                                <div class="col-sm-8">
                                    {{$warehouse->address}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Lattitude</label>
                                <div class="col-sm-8">
                                    {{$warehouse->lattitude}}
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Longitude</label>
                                <div class="col-sm-8">
                                    {{$warehouse->longitude}}
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    @include('admin.includes.admin_footer_inner')
    <!-- Page-Level Scripts -->
@stop
