@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>List of Review Rating</h5>

                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table id="rating-table" class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>User Name</th>
                                    <th>Product Name</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Date</th>
                                    <th>Status</th>
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
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/review_rating.js') }}"></script>
<script>
    $('.input-sm').attr('placeholder',"Rating,Review");
</script>
@stop