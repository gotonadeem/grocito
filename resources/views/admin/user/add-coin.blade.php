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
                        <h4 class="header-title m-t-0">Add Coin</h4>
                        
                    </div>
                        <p class="text-muted font-13 m-b-10"></p>
                        <div class="p-70">
                            {{ Form::open(array('url' => 'admin/user/stroe-coin/'.$user_id,'class'=>'form-horizontal','id'=>'add_coin','name'=>'add_coin' )) }}
                           <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="add_coin" id="coin" value="{!! old('coin') !!}" placeholder="Add Coin">
                                    <div class="error-message">{{ $errors->first('add_coin') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8">
                                    <button type="submit" name="stroe_coin" value="stroe_coin"  class="btn btn-primary waves-effect waves-light">
                                        Submit
                                    </button>
                                    <button type="reset"
                                            class="btn btn-default waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>

                </div>


            </div> <!-- container -->
        </div> <!-- container -->
    </div> <!-- content -->
    </div>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/sub_admin.js') }}"></script>
@stop

