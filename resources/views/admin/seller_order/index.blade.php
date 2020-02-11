@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Seller Order Report</h5>
                        <div class="ibox-tools">
						<a href="{{URL::to('admin/seller/verified-seller-list')}}" class="btn btn-primary pull-right">BACK</a>
                    </div>
                </div>
                <div class="ibox-content">
				   
                    <div class="table-responsive">
                        <table id="table" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Order Id</th>
								<th>S-Name</th>
                                <th>S-Username</th>
                                <th>S-Mobile</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Qty</th>
                                <th>Product</th>
                                <th>P-mode</th>
                                <th>AWB No</th>
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
@include('admin.includes.admin_right_sidebar')
<!-- Mainly scripts -->
<script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/dataTables/datatables.min.js') }}"></script>
<!-- Custom and plugin javascript -->

<script>
    ASSET_URL = '{{ URL::asset('public') }}/';
    BASE_URL='{{ URL::to('/') }}';
	var seller_id="<?=$id;?>";
</script>
<script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/seller_order.js') }}"></script>
@stop
