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
                        <h5>Active Customer List</h5>
					      
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
                                    <th>City</th>
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
    <!-- wallet deduct money model -->
    <div class="modal fade" id="walletDeductMoney" tabindex="-1" role="dialog" aria-labelledby="fareCopyModel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fareCopyModel">Deduct Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="alert alert-success amount-success" role="alert" style="display: none"> </div>
                <div class="alert alert-danger amount-error" role="alert" style="display: none"> </div>
                <div class="modal-body">
                    <div class="add-account-module">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>User Wallet Amount</label>
                            </div>
                            <div class="col-md-8">
                                <div > <i class="fa fa-rupee"></i> <span class="wallet_amount " ></span></div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>Deduct Amount</label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="deduct_amount" id="deduct_amount" class="form-control" min="1" required>
                                <span id="state-error" class="error state-error" style="display:none">This field is required</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>Deduct reason</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="deduct_reason" id="deduct_reason" class="form-control"  required>
                                <span id="deduct_reason_error" class="error deduct_reason_error" style="display:none">This field is required</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" id="user_id">
                    <button type="button" onclick="deductWalletAmount()" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- wallet deduct money model -->
    <div class="modal fade" id="walletAddMoney" tabindex="-1" role="dialog" aria-labelledby="fareCopyModel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fareCopyModel">Add Wallet Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="alert alert-success add-amount-success" role="alert" style="display: none"> </div>
                <div class="alert alert-danger add-amount-error" role="alert" style="display: none"> </div>
                <div class="modal-body">
                    <div class="add-account-module">

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>Add Amount</label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="add_amount" id="add_amount" class="form-control" min="1" required>
                                <span id="add-amount-error" class="error state-error" style="display:none">This field is required</span>
                            </div>
                        </div>
						
						 <div class="form-group row">
                            <div class="col-md-4">
                                <label>Add reason</label>
                            </div>
                            <div class="col-md-8">
                                <textarea type="text" name="add_reason" id="add_reason" class="form-control"  required></textarea>
                                <span id="add_reason_error" class="error add_reason_error" style="display:none">This field is required</span>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" id="user_id_add">
                    <button type="button" onclick="addWalletAmount()" class="btn btn-primary">Submit</button>
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
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/active_customer.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"name,email,mobile");
        function deductMoneyModel(userid) {
            $('#user_id').val(userid);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url: BASE_URL + '/admin/customer/get-user-wallet-amount',
                data:{userid:userid},
                success:function(data){
                    $(".wallet_amount").text(data.wallet_amount);
                    $('#walletDeductMoney').modal('show');
                }
            });

        }
        function deductWalletAmount() {
            var userid = $('#user_id').val();
            var deduct_amount = $('#deduct_amount').val();
            var deduct_reason = $('#deduct_reason').val();
            if(deduct_amount ==''){
                $('#state-error').show();
                return false;
            }
            $('#state-error').hide();
            if(deduct_reason ==''){
                $('#deduct_reason_error').show();
                return false;
            }


            $('#deduct_reason_error').hide();
            $('.loader_div').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url: BASE_URL + '/admin/customer/deduct-user-wallet-amount',
                data:{userid:userid,deduct_amount:deduct_amount,deduct_reason:deduct_reason},
                success:function(data){
                   if(data.status ==1){
                       setTimeout(function(){
                           $('.amount-success').html(data.message).show();
                           location.reload();
                       }, 2000);

                   }else if(data.status==0){
                        $('.amount-error').html(data.message).show();
                       $('.loader_div').hide();
                    }

                }
            });
        }
        function addMoneyModel(userid) {
            $('#user_id_add').val(userid);
            $('#walletAddMoney').modal('show');

        }
        function addWalletAmount() {
            var userid = $('#user_id_add').val();
            var add_amount = $('#add_amount').val();
            var add_reason = $('#add_reason').val();
            if(add_amount ==''){
                $('#add-amount-error').show();
                return false;
            }
            $('#add-amount-error').hide();

             if(add_reason ==''){
                $('#add_reason_error').show();
                return false;
            }


            $('#add_reason_error').hide();
			
            $('.loader_div').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url: BASE_URL + '/admin/customer/add-user-wallet-amount',
                data:{userid:userid,add_amount:add_amount,add_reason:add_reason},
                success:function(data){
                    if(data.status ==1){
                        setTimeout(function(){
                            $('.add-amount-success').html(data.message).show();
                            location.reload();
                        }, 2000);

                    }else if(data.status==0){
                        $('.add-amount-error').html(data.message).show();
                        $('.loader_div').hide();
                    }

                }
            });
        }
	</script>
@stop
