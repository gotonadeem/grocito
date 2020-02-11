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
                        <h4 class="header-title m-t-0">Edit Slider</h4>
                    </div>
                    <a href="{{ URL::to('admin/slider/slider-list') }}" class="pull-right btn btn-info btn-sm" ><i class="fa fa-info"></i> View All</a>
                    <!-- form start -->
                     {{ Form::model($slider,array('url' => 'admin/slider/updateSlider/'.$slider->id, 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')) }}
                   <div class="box-body">



                       <div class="form-group">
                           <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Slider Image</label>
                           <div class="col-sm-7">
                               <?PHP
                               $img=empty($slider->images) ? '':$slider->images;
                               ?>
                               <img src="{{ URL::asset('public/admin/uploads/slider_image/'.$img) }}" height="100" width="150">

                               <input type="file" class="form-control" name="images" id="images">
                               <div class="error-message">{{ $errors->first('images') }}</div>
                                   <input type="hidden" value="{{ $slider->images or ''}}" name="old_img">
                           </div>
                       </div>
                       <div class="form-group">
                           <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Title</label>
                           <div class="col-md-6">
                               {{Form::textarea('title',empty($slider->title) ? '' : $slider->title,['class'=>'form-control','id'=>'title','placeholder'=>'Enter Title'])}}
                               <div class="error-message">{{ $errors->first('title') }}</div>
                           </div>
                       </div>
                       <div class="form-group">
                           <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Link Type</label>
                           <div class="col-md-6">
                               <select class="form-control" name="link_type" onchange="getCatProductList(this.value)">
                                   <option value="category" {{$slider->link_type=='category'?'selected':''}}>Category</option>
{{--                                   <option value="product" {{$slider->link_type=='product'?'selected':''}}>Product</option>--}}

                               </select>

                           </div>
                       </div>
					   <div class="form-group">
                          <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Link Category</label>
                          <div class="col-md-6">
                             <select class="form-control" name="link" onChange="getProduct(this.value)">
                                     <option value="">Select Category</option>
                                    <?PHP foreach($category_list as $vs): 
									                    if($slider->link==$vs->id):
									                    ?>
                                      <option  selected value="<?=$vs->id?>"><?=$vs->name?></option>
									                    <?PHP else: ?>
									                    <option  value="<?=$vs->id?>"><?=$vs->name?></option>
                                      <?PHp  endif; endforeach; ?>
                              </select>
                              <div class="error-message">{{ $errors->first('link') }}</div>
                          </div>
                      </div>
                       <div class="form-group" id="product_select" style="display: none">
                           <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Link Product</label>
                           <div class="col-md-6">
                               <select class="form-control" name="p_link" id="product_id">
                                   @foreach($product_list as $pro)
                                       @if($pro->id == $slider->p_link)
                                           <option value="{{$pro->id}}" selected>{{$pro->name}}</option>
                                       @else
                                   <option value="{{$pro->id}}" >{{$pro->name}}</option>
                                       @endif
                                    @endforeach
                               </select>
                               <div class="error-message">{{ $errors->first('p_link') }}</div>
                           </div>
                       </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button type="submit" id="submit" value="submit" class="btn btn-primary">Submit</button>
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
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'title' );
    </script>
    <script>
        $(document).ready(function() {
            var type = '<?php echo $slider->link_type;?>';
            getCatProductList(type);
        });
        BASE_URL="{{URL::to('/')}}";
        function getCatProductList(type) {
            if(type =='category'){
                $('#product_select').hide();
            }else if(type =='product'){
                $('#product_select').show();
            }
        }
        function getProduct(cat_id)
        {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL+'/admin/slider/get-product-list',
                type: 'POST',
                data: {cat_id: cat_id },
                success: function (data) {
                    $("#product_id").html(data);
                },
                error: function () {
                    console.log('There is some error. Please try again.');
                }
            });
        }
    </script>
@stop
