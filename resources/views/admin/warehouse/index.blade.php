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
                        <h5>Warehouse List</h5>
                    <div class="text-right"><a href="{{ URL::to('admin/warehouse/add-warehouse') }}" class="btn btn-info">Add New</a>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table id="warehouse-table" class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Warehouse Name</th>
                                    <th>City</th>
                                    <th>Pincode</th>
                                    <th>Date</th>
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
	
	<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
  {{ Form::open(array('url' => 'admin/warehouse/assign-subadmin-warehouse','class'=>'form-horizontal','id'=>'add_warehouse','name'=>'add_warehouse')) }}

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Subadmin</h4>
      </div>
	  <input type="hidden" name="id" id="id">
      <div class="modal-body" id="data">
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="submit" value="subadmin">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>

    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    @include('admin.includes.admin_footer_inner')
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/warehouse.js') }}"></script>
    <script>
        $('.input-sm').attr('placeholder',"name,pincode");
    </script>
@stop
