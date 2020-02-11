@extends('admin.layout.admin')
@section('content')

    <link href="{{config('image.url.editor.datepicker')}}/datepicker3.css" rel="stylesheet">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <strong>Edit Packages</strong>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-info">
                <div class="box-header">
                    <a href="{{ URL::to('admin/packages/package-list') }}" class="pull-right btn btn-info btn-sm" ><i class="fa fa-info"></i> View All</a>
                </div><!-- /.box-header -->
            </div>
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">

                    <!-- general form elements -->
                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Packages</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        {{-- Form::model($packages,array('route' => ['admin/packages/edit/'.$packages->id],'class'=>'form-horizontal','id'=>'plan_form','method'=>'put','enctype'=>'multipart/form-data')) --}}
                        {{ Form::model($packages,array('url' => 'admin/packages/edit/'.$packages->id,'class'=>'form-horizontal')) }}

                        <div class="box-body">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Package Name</label>
                                <div class="col-sm-7">
                                    {{Form::text('package_name',empty($packages->package_name) ? '' : $packages->package_name,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Package Name'])}}
                                    <div class="error-message">{{ $errors->first('package_name') }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Invested Amount From</label>
                                <div class="col-sm-7">
                                    {{Form::text('invested_amount_from',empty($packages->invested_amount_from) ? '' : $packages->invested_amount_from,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Invested Amount From'])}}
                                    <div class="error-message">{{ $errors->first('invested_amount_from') }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Invested Amount To</label>
                                <div class="col-sm-7">
                                    {{Form::text('invested_amount_to',empty($packages->invested_amount_to) ? '' : $packages->invested_amount_to,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Invested Amount To'])}}
                                    <div class="error-message">{{ $errors->first('invested_amount_to') }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Daily Roi</label>
                                <div class="col-sm-7">
                                    {{Form::text('daily_roi',empty($packages->daily_roi) ? '' : $packages->daily_roi,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Daily Roi'])}}
                                    <div class="error-message">{{ $errors->first('daily_roi') }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Referral Income</label>
                                <div class="col-sm-7">
                                    {{Form::text('referral_income',empty($packages->referral_income) ? '' : $packages->referral_income,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Referral Income'])}}
                                    <div class="error-message">{{ $errors->first('referral_income') }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Reword Bonus</label>
                                <div class="col-sm-7">
                                    {{Form::text('reword_bonus',empty($packages->reword_bonus) ? '' : $packages->reword_bonus,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Reword Bonus'])}}
                                    <div class="error-message">{{ $errors->first('reword_bonus') }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Days On Roi</label>
                                <div class="col-sm-7">
                                    {{Form::text('days_on_roi',empty($packages->days_on_roi) ? '' : $packages->days_on_roi,['class'=>'form-control','id'=>'account_holder_name','placeholder'=>'Days On Roi'])}}
                                    <div class="error-message">{{ $errors->first('days_on_roi') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <button type="submit" id="submit" value="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div><!-- /.box -->
                </div><!--/.col (left) -->

            </div>   <!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop