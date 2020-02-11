@extends('admin.layout.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header ibox-content">
        <h3>
            <strong>Change Password</strong>
        </h3>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box-info ibox-content">
                    <div class="box-header with-border">
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php echo Form::open(array('url' => 'admin/change_password', 'class' => 'form-horizontal','id'=>'change-password')) ?>
                        <div class="box-body ">
                            <div class="form-group">
                                <label for="current_password" class="col-sm-3 control-label">Enter Current Password <span
                                            class="star_important">*</span></label>
                                <div class="col-sm-4">
                                    <input type="password" name="current_password" class="form-control"
                                           id="current_password" placeholder="Current Password"
                                           aria-describedby="current_password"/>
                                    <div class="error-message">{{ $errors->first('current_password') }}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Enter New Password <span
                                            class="star_important">*</span></label>
                                <div class="col-sm-4">
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="New Password" aria-describedby="password"/>
                                    <div class="error-message">{{ $errors->first('password') }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="col-sm-3 control-label">Confirm Password <span
                                            class="star_important">*</span></label>
                                <div class="col-sm-4">
                                    <input type="password" name="password_confirmation" class="form-control"
                                           id="password_confirmation" placeholder="Confirm Password"
                                           aria-describedby="password_confirmation"/>
                                    <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                                </div>
                            </div>

                        </div><!-- /.box-body -->
                        <div class="box-footer ">
                            <div class="col-sm-offset-3 col-sm-2">
                                <button type="submit" class="btn btn-info">Change Password</button>
                            </div>
                        </div><!-- /.box-footer -->
                        {{ Form::close() }}
                </div><!-- /.box -->
            </div>
        </div>

    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->@include('admin.includes.admin_right_sidebar')    <!-- Mainly scripts -->    <script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>    <script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>    <script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>    <script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>    <script src="{{ URL::asset('public/admin/js/plugins/dataTables/datatables.min.js') }}"></script>    <!-- Custom and plugin javascript -->    <script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>    <script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>    <!-- Page-Level Scripts -->    <script>        ASSET_URL = '{{ URL::asset('public') }}/';        BASE_URL='{{ URL::to('/') }}';    </script>
<script>
	$("#current_password").on("keydown", function (e) {
	return e.which !== 32;
	});
	$("#password").on("keydown", function (e) {
	return e.which !== 32;
	});
	$("#password_confirmation").on("keydown", function (e) {
	return e.which !== 32;
	});
</script>
@stop