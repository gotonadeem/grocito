<?php
/**
 * Created by PhpStorm.
<<?php
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
						<h5>View Subadmin Permission</h5>
						<div class="text-right"><a href="{{ URL::to('admin/subadmin/view-all-subadmin') }}" class="btn btn-info">Back</a>

						</div>
						<div class="ibox-content">
							<div class="p-70">
								<div class="form-group row">
									<label for="inputEmail3" class="col-sm-4 form-control-label">Username<span class="text-danger">*</span></label>
									<div class="col-sm-7">
										{{$users->username}}
									</div>
								</div>
								<div class="form-group row">
									<label for="inputEmail3" class="col-sm-4 form-control-label">Email<span class="text-danger">*</span></label>
									<div class="col-sm-7">
										{{$users->email}}

									</div>
								</div>
								<div class="form-group row">
									<label for="hori-pass2" class="col-sm-4 form-control-label">Permissions
										<span class="text-danger">*</span></label>

									<div class="col-sm-8">
										<ul class="row permission-list">
											@foreach ($permission as $p)
												<li class="col-sm-6">{{str_replace('_', ' ', $p->access_permission)}}
													<ul class="row">
														<li>{{$p->action}}</li>

													</ul>
												</li>

											@endforeach
										</ul>

									</div>


								</div>
								<div class="form-group row">
									<label for="hori-pass2" class="col-sm-4 form-control-label">Warehouse
										<span class="text-danger">*</span></label>
									<div class="col-sm-8">
									<?php $wareData = Helper::getSubadminWarehouse($users->id);
											if($wareData){ ?>
												<p><label>Name :</label> {{$wareData->name}}</p>
												<p><label>Address :</label> {{$wareData->address}}</p>
												<p><label>Pincode :</label> {{$wareData->pincode}}</p>
										<?php 	}
										?>

									</div>


								</div>
							</div>
						</div>
					</div><!-- end col -->
				</div>
				<!-- end row -->

			</div> <!-- container -->
		</div> <!-- content -->
	</div>
	@include('admin.includes.admin_right_sidebar')

	<script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
	<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/sub_admin.js') }}"></script>
@stop

