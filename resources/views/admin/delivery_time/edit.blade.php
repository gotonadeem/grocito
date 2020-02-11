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
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <div class="row">
                            <div class="col-xs-6">
                            <h5>Edit Warehouse Details</h5>
                            </div>
                            <div class="col-xs-6">
                            <a href="{{ URL::to('admin/warehouse/warehouse-list') }}" class="btn btn-info">Back</a>
                            </div>
                        </div>

                        {{ Form::model($warehouse,array('url' => 'admin/warehouse/update/'.$warehouse->id,'class'=>'form-horizontal')) }}
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">City<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <select class="form-control city" name="city_id" onchange="getPincode(this.value)">
                                    <option value="">Select City</option>
                                    <?PHP foreach($cityList as $vs): ?>
                                    <?PHP if($warehouse->city_id == $vs->id): ?>
                                    <option selected value="<?=$vs->id?>">
                                        <?=$vs->name?>
                                    </option>
                                    <?PHP else: ?>
                                    <option value="<?=$vs->id?>">
                                        <?=$vs->name?>
                                    </option>
                                    <?PHP endif; ?>
                                    <?PHp endforeach; ?>
                                </select>
                                <div class="error-message">{{ $errors->first('provider_id') }}</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Pincode<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <select class="form-control pincode" name="pincode[]" id="pincode" multiple>
                                    <?php
                                    $pinArr = explode(",", $warehouse->pincode);
                                    foreach ($pincodeList as $pinList ) {
                                    $selected = in_array( $pinList->pincode, $pinArr ) ? ' selected="selected" ' : '';
                                    ?>
                                    <option value="<?php echo $pinList->pincode; ?>" <?php echo $selected; ?>><?php echo $pinList->pincode; ?></option>
                                    <?php } ?>
                                </select>
                                <div class="error-message">{{ $errors->first('pincode') }}</div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Warehouse Name<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                {{Form::text('name',$warehouse->name,['class'=>'form-control','id'=>'name','placeholder'=>'name'])}}
                                <div class="error-message">{{ $errors->first('name') }}</div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Warehouse Pincode<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                {{Form::text('warehouse_pincode',$warehouse->warehouse_pincode,['class'=>'form-control','id'=>'warehouse_pincode','placeholder'=>'warehouse pincode'])}}
                                <div class="error-message">{{ $errors->first('warehouse_pincode') }}</div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Address<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                {{Form::text('address',$warehouse->address,['class'=>'form-control','id'=>'address','placeholder'=>'address'])}}
                                <div class="error-message">{{ $errors->first('address') }}</div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Lattitude<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                {{Form::text('lattitude',$warehouse->lattitude,['class'=>'form-control','id'=>'lattitude','placeholder'=>'lattitude'])}}
                                <div class="error-message">{{ $errors->first('lattitude') }}</div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 form-control-label">Longitude<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                {{Form::text('longitude',$warehouse->longitude,['class'=>'form-control','id'=>'longitude','placeholder'=>'longitude'])}}
                                <div class="error-message">{{ $errors->first('longitude') }}</div>

                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-8 col-sm-offset-4">
                                <button type="submit" name="add_user" value="add_user"  class="btn btn-primary waves-effect waves-light">
                                    Update
                                </button>

                            </div>
                        </div>
                        {{ Form::close() }}


                    </div>
                </div>
            </div>
        </div>
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
        <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
        <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/warehouse.js') }}"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
        <script type="text/javascript">
            //$(".pincode").select2();
        </script>
        <script>
            $(".city").select2();
            $(".pincode").select2({
                tags: false,
                placeholder: "Select a pincode",
                tokenSeparators: [',', ' ']
            })
        </script>
@stop
