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
                        <h5>Customer List</h5>
					      <a href="{{URL::to('admin/raising-list')}}" class="btn btn-primary pull-right">All Complaint</a>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                                <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                    <th>Sr.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
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
    <form id="support_form" name="support_form">
        <div class="modal fade" id="addRaisingComplaint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Get Support</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-success success_message" style="display: none"></div>
                    <div class="modal-body">
                        <div class="add-account-module">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label>Title</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" id="title" name="title" class="form-control" value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label>Problem</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="problem" row="5" id="problem"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label>Solution</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="solution" row="5" id="solution"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="" id="user_id">
                        <button type="button" onclick="addRaisingComplain()" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/raising_complain.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"name,email,mobile");
	</script>
    <script>
        function showModel(id) {
            $('#user_id').val(id);
            $('#addRaisingComplaint').modal('show');
        }
        function addRaisingComplain() {
            var userId = $('#user_id').val();
            var title = $('#title').val();
            var problem = $('#problem').val();
            var solution = $('#solution').val();
            $(".loader-div").show();
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL + '/admin/register-complaint',
                type: 'POST',
                data:{userId:userId,title:title,problem:problem,solution:solution},
                success: function (data) {
                    setTimeout(function() {
                        $('.success_message').text('Request add successfully').show();
                        $("#addRaisingComplaint").hide();
                        location.reload();
                    }, 1000);
                    $(".loader-div").hide();
                },

                error: function (error) {

                    console.log('erorrr');

                }

            });
        }
    </script>
@stop
