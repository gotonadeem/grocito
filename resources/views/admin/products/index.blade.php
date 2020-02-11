@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>List of Verified Product</h5>
					
                        <div class="text-right"><a href="{{ URL::to('admin/product/create-product') }}" class="btn btn-info">Add New</a>
                        </div>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table id="property-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Category</th>
                                        <th>Product Id</th>
                                        <th>Product Name</th>
                                        <th>City</th>
                                        <th>Seller</th>
                                        <th>Sunday Bazar</th>
                                        <th>Grocito Exclusive</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
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
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/product.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"Id,P-Name,S-name,Category");
	</script>
@stop