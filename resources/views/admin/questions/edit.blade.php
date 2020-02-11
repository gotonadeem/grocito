
@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Edit Question</h5>
                        <div class="text-right"><a href="{{ URL::to('admin/section/section-list') }}" class="btn btn-info">View All</a>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        <!-- form start -->
                    {{ Form::model($question,array('url' => ['admin/question/updateQuestion/'.$question->id.'/'.$id2.'/'.$id3],'class'=>'form-horizontal','method'=>'post','enctype'=>'multipart/form-data')) }}
                            <div class="form-group row">
                                <label for="title" class="col-md-3 control-label">Title <span class="star_important">*</span></label>
                                <div class="col-md-6">
                                    {{Form::text('title',NULL,['class'=>'form-control','placeholder'=>'Enter name'])}}
                                    <div class="error-message">{{ $errors->first('name') }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="exampleInputEmail1" class="col-sm-3 form-control-label">Description</label>
                                <div class="col-md-6">
                                    {{Form::textarea('description',empty($question->description) ? '' : $question->description,['class'=>'form-control','id'=>'title','placeholder'=>'Enter Description'])}}
                                    <div class="error-message">{{ $errors->first('description') }}</div>
                                </div>
                            </div>
                        <div class="form-group row">
                            <div class="col-sm-8 col-sm-offset-4">
                                <button name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                        {{ Form::close() }}
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
        <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
        <script>
            //CKEDITOR.replace( 'title' );
        </script>
        <!-- Page-Level Scripts -->
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
    </div>
@stop