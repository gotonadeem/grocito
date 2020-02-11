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
                        <h5>Pincodes List</h5>
                         <div class="text-right"><a href="{{ URL::to('admin/city/index') }}" class="btn btn-info">Back</a>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table id="pincode-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                         <th>Sr.</th>
                                         <th>City Name</th>
                                         <th>Pincode</th>                                         
                                         <th>Action</th>
                                    </tr>
                                    </thead>
                                   
                                </table>
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
     <script>
            ASSET_URL = '{{ URL::asset('public') }}/';
            BASE_URL='{{ URL::to('/') }}';
           var city_id="<?=$city_id;?>";
        </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
     <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/citypincode.js') }}"></script>
    
    
    

@stop
