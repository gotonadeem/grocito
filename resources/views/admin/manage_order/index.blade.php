@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Order List</h5>
                       
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="order-table" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>O-Id</th>
                                <th>C-Name</th>
                                <th>C-Mob</th>
								 <th>O-Status</th>
                               
                                <th>P-mode</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
							</thead>
							<tbody>
							@foreach(@$data as $ks=>$vs)
							<tr>
                                <td>{{$ks+1}}</td>
                                <td>{{$vs->order_id}}</td>
                                <td>{{$vs->name}}</td>
                                <td>{{$vs->mobile}}</td>
								
                                <td>{{$vs->status}}</td>
                               
                                <td>{{$vs->payment_mode}}</td>
                                <td>{{date('d-m-Y',strtotime($vs->created_at))}}</td>
                                <td><a href="{{URL::to('admin/accept-order/'.$vs->d_id)}}">Accept</a></td>
                            </tr>
							@endforeach
							</tbody>
                            
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
<script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
<!-- Page-Level Scripts -->
<script>
    ASSET_URL = '{{ URL::asset('public') }}/';
    BASE_URL='{{ URL::to('/') }}';
</script>
@stop
