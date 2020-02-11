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
                        <h5>Delivery Time List</h5>
                    <div class="ibox-content">
                        <div class="table-responsive">
                                <table id="time-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                    <th>Sr.</th>
                                    <th>Name</th>
                                    <th>State</th>
                                    <th>Standard Time Interval(Hr)</th>
                                    <th>Express Time(Min.)</th>
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
        <form id="support_form" name="support_form">
            <div class="modal fade" id="addDeliveryTime" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Delivery Time</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="alert alert-success success_message" style="display: none"></div>
                        <div class="modal-body">
                            <div class="add-account-module">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label>Time Interval</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="time_interval" name="time_interval" class="form-control" value="">
                                        <span id="time-interval-error" class="error"></span>
                                    </div>
                                </div>

                            </div>
                            <div class="add-account-module">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label>Start Time (Ex:10:00AM)</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="start_time" name="start_time" placeholder="10:00AM" class="form-control" value="10:00AM">
                                        <span id="start-time-error" class="error"></span>
                                    </div>
                                </div>

                            </div>
                            <div class="add-account-module">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label>End Time (Ex:07:00PM)</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="end_time" name="end_time" placeholder="07:00PM" class="form-control" value="07:00PM">
                                        <span id="end-time-error" class="error"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" value="" id="city_id">
                            <button type="button" onclick="addDeliveryTime()" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="addExpressText" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Express Time</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-success success_message" style="display: none"></div>
                    <div class="modal-body">
                        <div class="add-account-module">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label>Express Time(Minute)</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="number" id="express_time" name="express_time" class="form-control" value="" min="1">
                                    <span id="express-time-error" class="error" ></span>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="" id="city_id">
                        <button type="button" onclick="addExpressTime()" class="btn btn-primary">Submit</button>
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
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/delivery_time.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"city");
	</script>

        <script>
            function showModel(id,interval,start_time,end_time) {

                $('#city_id').val(id);
                $('#time_interval').val(interval);
                $('#start_time').val(start_time);
                $('#end_time').val(end_time);
                $('#addDeliveryTime').modal('show');
            }
            function showExpressModel(id,express_time) {

                $('#city_id').val(id);
                $('#express_time').val(express_time);
                $('#addExpressText').modal('show');
            }
            function addDeliveryTime() {
                var city_id = $('#city_id').val();
                var time_interval = $('#time_interval').val();
                var start_time = $('#start_time').val();
                var end_time = $('#end_time').val();

                if(time_interval ==''){
                    $('#time-interval-error').text('This field is required').show();
                    return false;
                }
                if(start_time ==''){
                    $('#start-time-error').text('This field is required').show();
                    return false;
                }
                if(end_time ==''){
                    $('#end-time-error').text('This field is required').show();
                    return false;
                }
                $(".loader_div").show();
                jQuery.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: BASE_URL + '/admin/update-delivery-time',
                    type: 'POST',
                    data:{city_id:city_id,time_interval:time_interval,start_time:start_time,end_time:end_time},
                    success: function (data) {
                        setTimeout(function() {
                            $('.success_message').text('Update successfully').show();
                            $(".loader_div").hide();
                            location.reload();
                        }, 2000);

                    },

                    error: function (error) {

                        console.log('erorrr');

                    }

                });
            }
            function addExpressTime() {
                var city_id = $('#city_id').val();
                var express_time = $('#express_time').val();

                if(express_time ==''){
                    $('#express-time-error').text('This field is required').show();
                    return false;
                }

                $(".loader_div").show();
                jQuery.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: BASE_URL + '/admin/update-express-time',
                    type: 'POST',
                    data:{city_id:city_id,express_time:express_time},
                    success: function (data) {
                        setTimeout(function() {
                            $('.success_message').text('Update successfully').show();
                            $(".loader_div").hide();
                            location.reload();

                        }, 2000);

                    },

                    error: function (error) {

                        console.log('erorrr');

                    }

                });
            }

        </script>
@stop
