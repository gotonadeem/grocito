@extends('admin.layout.admin')
@section('content')
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content" style="
    padding: 0 0px 0px 0px;
    margin-top: 29px;">
            <div class="container">
                <!-- end row -->
                <br>
                <div class="row coin-add" style="background:#fff; padding:10px;">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">(<?PHP print_r($user); ?>)Add Note</h4>
                    </div>
                <!-- form start -->
                {{ Form::open(array('url' => 'admin/note/storeNote','class'=>'form-horizontal','enctype'=>'multipart/form-data','name'=>'add_faq')) }}
                  <div class="box-body">
				   
					  
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Heading<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <select class="form-control" name="heading">
							  <option value="account_blocked">Account Blocked</option>
							  <option value="account_not_verified_yet">Account is not verified Yet</option>
							  </select>
                              <div class="error-message">{{ $errors->first('heading') }}</div>
                          </div>
                      </div>

                      <div class="form-group">
                          <label  class="col-md-3 control-label">Description<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              {{Form::textarea('message',NULL,['class'=>'form-control','id'=>'editor1','placeholder'=>'Enter description'])}}
                              <div class="error-message">{{ $errors->first('message') }}</div>
                          </div>
                      </div>

                  </div><!-- /.box-body -->

                    <div class="box-footer">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                    <button type="submit" id="submit" name="add_package" class="btn btn-primary">Submit</button>
                    
                  </div>
                  </div>
                {{ Form::close() }}
               </div> <!-- container -->
        </div> <!-- container -->
    </div> <!-- content -->
    </div>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/sub_admin.js') }}"></script>
 @stop
