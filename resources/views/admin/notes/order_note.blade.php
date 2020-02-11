@extends('admin.layout.admin')
@section('content')
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content" style="
    padding: 0 0px 0px 0px;
    margin-top: 29px;">
            <div class="container-fluid">
                <!-- end row -->
                <br>
                <div class="row coin-add" style="background:#fff; padding:10px;">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Add Note ({{$order->order_id}}) </h4>
						<a class="pull-right btn btn-primary" href="{{URL::to(URL::previous())}}">Back</a>
                    </div>
					
					
                <!-- form start -->
                {{ Form::open(array('url' => 'admin/order-note/storeNote','class'=>'form-horizontal','enctype'=>'multipart/form-data','name'=>'add_faq')) }}
                  <div class="box-body">
				    <input type="hidden" name="order_id" value="{{$order->id}}">
					  
                      <div class="form-group">
                          <label for="exampleInputEmail1" class="col-md-3 control-label">Heading<span class="star_important">*</span></label>
                          <div class="col-md-6">
                              <select class="form-control" name="heading">
							  <option value="order_cancelled">Order Cancelled</option>
							  <option value="order_pending">Order pending</option>
							  <option value="comment">Comment</option>
							  </select>
                              <div class="error-message">{{ $errors->first('heading') }}</div>
                          </div>
                      </div>

                      <div class="form-group">
                          <label  class="col-md-3 control-label">Message<span class="star_important">*</span></label>
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
			   
			   <div class="row">
					@foreach($order_notes as $vs)
					<div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title box-heading">
                        <h5 class="text-formate">{{str_replace("_"," ",$vs->heading)}} &nbsp;&nbsp;  </h5> <a class="pull-right" id="{{$vs->id}}" onclick="deleteItemOrder(this.id)" href="javascript:void(0)"><i style="font-size: 22px;
    color: gainsboro;" class="fa fa-trash"></i></a>
                    </div>
					
                    <div class="ibox-content" style="border-style: ridge;box-shadow: 2px 2px 2px slategrey;">
					<p><b class='text-success'>Date: {{$vs->created_at}}</b></p>
					<b>{!!$vs->message!!}</b>
                    </div>
					
                </div>
            </div>
			@endforeach
			
					</div>
        </div> <!-- container -->
    </div> <!-- content -->
    </div>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/note.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'editor1' );
        BASE_URL ="{{URL::to('/')}}";
    </script>
 @stop
