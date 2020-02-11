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
                    <div class="col-sm-10">
                        <h4 class="header-title m-t-0">Add Question</h4>
						 <a href="{{ URL::previous() }}" class="btn btn-primary pull-right">Back</a>
                    </div>
                <!-- form start -->
                {{ Form::open(array('url' => 'admin/question/storeQuestion','class'=>'form-horizontal','enctype'=>'multipart/form-data','name'=>'add_faq')) }}
                 <input type="hidden" name="faq_id" value="<?=Request::segment(4)?>">                 
                 <input type="hidden" name="section_id" value="<?=Request::segment(5)?>">                 
				 <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Question Title<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <input type="text" name="title" value="{{ old('title')}}" class="form-control"  placeholder="Enter Title">
                              <div class="error-message">{{ $errors->first('title') }}</div>
                          </div>
                      </div>
					  <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Select Topic<span class="star_important">*</span></label>
                          <div class="col-md-6">
                               <select class="form-control" name="topic_id">
							    <option value="">Select Topic</option>
								<?PHP foreach($faq_list as $vs): ?>
								  <option value="{{$vs->id}}">{{$vs->title}}</option>
								<?PHP endforeach; ?>
                               </select>  							   
							 <div class="error-message">{{ $errors->first('title') }}</div>
                          </div>
                      </div>

                      <div class="form-group">
                          <label  class="col-md-3 control-label">Description<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              {{Form::textarea('description',NULL,['class'=>'form-control','id'=>'editor1','placeholder'=>'Enter description'])}}
                              <div class="error-message">{{ $errors->first('description') }}</div>
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
