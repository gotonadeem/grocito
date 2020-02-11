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
					      <a href="#" class="btn btn-primary pull-right">All Complaint</a>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                                <table id="list-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                    <th>Sr.</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Complaint Id</th>
                                    <th>Title</th>
                                    <th>Problem</th>
                                    <th>Solution</th>
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
        <div class="modal fade" id="addSolution" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <label>Solution</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="solution" row="5" id="solution"></textarea>
                                    <span class="solution_error error" style="display: none">Solution is required</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="" id="id">
                        <button type="button" onclick="addRaisingSolution()" class="btn btn-primary">Submit</button>
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
        function showSolutionModel(id) {
            $('#id').val(id);

            $('#addSolution').modal('show');
        }
        function addRaisingSolution() {
            var id = $('#id').val();
            var solution = $('#solution').val();
            if(solution ==''){
                $('.solution_error').show();
                return false;
            }
            $('.solution_error').hide();
            $(".loader-div").show();
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL + '/admin/add-solution',
                type: 'POST',
                data:{solution:solution,id:id},
                success: function (data) {
                    setTimeout(function() {
                        $('.success_message').text('Solution add successfully').show();
                        $("#addRaisingSolution").hide();
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
